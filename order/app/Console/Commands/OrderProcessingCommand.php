<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Console\Command;

class OrderProcessingCommand extends Command
{
    protected OrderService $orderService;

    protected $signature = 'order:processing';

    protected $description = 'Consume messages from the order.created queue and process them';

    public function __construct(OrderService $orderService)
    {
        parent::__construct();

        $this->orderService = $orderService;
    }

    public function handle(): void
    {
        $this->info('Order processing is running...');

        $params = [
            'exchange' => 'order',
            'exchange_type' => 'fanout',
            'queue_force_declare' => true,
            'queue_exclusive' => true,
            'persistent' => true
        ];

        Amqp::consume('order.processing', fn ($message, $resolver) => $this->processMessage($message, $resolver), $params);

        $this->info('ProductCreatedConsumer finished.');
    }

    private function processMessage($message, $resolver): void
    {
        try {
            $this->info("start processing message at " . now());

            $data = json_decode($message->body, true);

            $this->orderService->process($data['id']);

            $resolver->acknowledge($message);

            $this->info("✅ Message processed successfully");

        } catch (\Exception $e) {
            $this->error("❌ Error processing message: " . $e->getMessage());

            $resolver->reject($message, false);
        }
    }
}