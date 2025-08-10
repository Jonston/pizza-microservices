<?php

namespace App\Console\Commands;

use App\Enums\OrderEventEnum;
use App\Services\OrderService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Jonston\AmqpLaravel\AMQPService;
use PhpAmqpLib\Message\AMQPMessage;

class OrdersWatcher extends Command
{
    protected OrderService $orderService;

    protected AMQPService $amqpService;

    protected $signature = 'watch:orders';

    protected $description = 'Consume messages from the orders queue';

    public function __construct(OrderService $orderService, AMQPService $amqpService)
    {
        parent::__construct();

        $this->orderService = $orderService;

        $this->amqpService = $amqpService;
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $this->info('Starting Order Created Watcher...');

        $this->amqpService->consume('orders', function (AMQPMessage $message) {
            $this->processMessage($message);
        });
    }

    private function processMessage(AMQPMessage $message): void
    {
        try {
            $data = json_decode($message->getBody(), true);

            $order = $data['order'] ?? null;

            if ( ! isset($order)) {
                Log::channel('amqp')->error('Order not found', [
                    'data' => $data,
                ]);

                $message->nack(false, true);

                return;
            }

            match ($data['event'] ?? null) {
                OrderEventEnum::ORDER_CREATED => $this->orderService->process($order['id']),
                OrderEventEnum::ORDER_PROCESSED => $this->orderService->delivery($order['id']),
                OrderEventEnum::ORDER_DELIVERED => $this->orderService->complete($order['id']),
                default => Log::channel('amqp')->warning('Unknown event', $data['event'] ?? 'unknown'),
            };

            $message->ack();

            Log::channel('amqp')->info('Message processed successfully', $data);

        } catch (Exception $e) {
            $this->error('Error processing message: ' . $e->getMessage());

            Log::channel('amqp')->error('Error processing message', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $message->nack(false, true);
        }
    }
}