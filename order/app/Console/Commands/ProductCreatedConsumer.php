<?php

namespace App\Console\Commands;

use App\Services\ProductService;
use Illuminate\Console\Command;
use Jonston\AmqpLaravel\AMQPService;

class ProductCreatedConsumer extends Command
{
    protected ProductService $productService;

    protected AMQPService $amqpService;

    protected $signature = 'amqp:consume-product-created';

    protected $description = 'Consume messages from the product.created queue and process them';

    public function __construct(ProductService $productService, AMQPService $amqpService)
    {
        parent::__construct();

        $this->productService = $productService;

        $this->amqpService = $amqpService;
    }

    public function handle(): void
    {
        $this->info('ProductCreatedConsumer is running...');

        $this->amqpService->consume('catalog', function ($message, $resolver) {
            $this->processMessage($message, $resolver);
        });

        $this->info('ProductCreatedConsumer finished.');
    }

    private function processMessage($message, $resolver): void
    {
        try {
            $this->info("start processing message at " . now());

            $data = json_decode($message->body, true);

            $this->productService->create($data);

            $resolver->acknowledge($message);

            $this->info("✅ Message processed successfully");

        } catch (\Exception $e) {
            $this->error("❌ Error processing message: " . $e->getMessage());

            $resolver->reject($message, false);
        }
    }
}