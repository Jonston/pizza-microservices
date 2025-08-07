<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Jonston\AmqpLaravel\AMQPService;
use PhpAmqpLib\Message\AMQPMessage;

class OrderProcessingCommand extends Command
{
    protected OrderService $orderService;

    protected AMQPService $amqpService;

    protected $signature = 'order:processing';

    protected $description = 'Consume messages from the orders queue and process them';

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
        $this->info('Order processing is running...');

        $this->amqpService->consume('orders', function (AMQPMessage $message) {
            $this->processMessage($message);
        });

        $this->info('ProductCreatedConsumer finished.');
    }

    private function processMessage(AMQPMessage $message): void
    {
        try {
            $data = json_decode($message->getBody(), true);

            $this->orderService->process($data['id']);

            $message->ack();

            Log::channel('amqp')->info('Order processed successfully', $data);

        } catch (Exception $e) {
            Log::channel('amqp')->error('Error processing order', [
                'error' => $e->getMessage(),
                'order_id' => $data['id'] ?? null,
                'message_id' => $message->get('message_id'),
            ]);

            $message->nack(false, true);
        }
    }
}