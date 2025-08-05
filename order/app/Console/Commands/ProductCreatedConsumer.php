<?php

namespace App\Console\Commands;

use App\Services\ProductService;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Console\Command;

class ProductCreatedConsumer extends Command
{
    protected ProductService $productService;

    protected $signature = 'amqp:consume-product-created';

    protected $description = 'Consume messages from the product.created queue and process them';

    public function __construct(ProductService $productService)
    {
        parent::__construct();

        $this->productService = $productService;
    }

    public function handle(): void
    {
        $this->info('ProductCreatedConsumer is running...');

        $params = [
            'routing' => '',
            'exchange' => 'product_fanout',
            'exchange_type' => 'fanout',
            'queue_force_declare' => true,
            'queue_exclusive' => true,
            'persistent' => true
        ];

        Amqp::consume('', fn ($message, $resolver) => $this->processMessage($message, $resolver), $params);

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