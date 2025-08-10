<?php

namespace App\Console\Commands;

use App\Services\ProductService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Jonston\AmqpLaravel\AMQPService;
use PhpAmqpLib\Message\AMQPMessage;

class ProductsWatcher extends Command
{
    protected ProductService $productService;

    protected AMQPService $amqpService;

    protected $signature = 'watch:catalog';

    protected $description = 'Consume messages from the products queue';

    public function __construct(ProductService $productService, AMQPService $amqpService)
    {
        parent::__construct();

        $this->productService = $productService;

        $this->amqpService = $amqpService;
    }

    public function handle(): void
    {
        $callback = function (AMQPMessage $message) {
            $this->processMessage($message);
        };

        $this->amqpService->consume(
            queue: 'products.created',
            callback: $callback,
            exchange: 'catalog_exchange',
            routingKey: 'products.created'
        );
    }

    private function processMessage(AMQPMessage $message): void
    {
        try {
            $data = json_decode($message->getBody(), true);

            $this->productService->create($data);

            $message->ack();

            Log::channel('amqp')->info('Message processed successfully', $data);
        } catch (\Exception $e) {
            $message->nack(false, true);

            Log::channel('amqp')->error('Error processing message', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}