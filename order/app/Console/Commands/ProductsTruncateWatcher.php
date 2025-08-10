<?php

namespace App\Console\Commands;

use App\Services\ProductService;
use Illuminate\Console\Command;
use Jonston\AmqpLaravel\AMQPService;
use PhpAmqpLib\Message\AMQPMessage;

class ProductsTruncateWatcher extends Command
{
    protected AMQPService $amqpService;

    protected ProductService $productService;

    public function __construct(AMQPService $amqpService, ProductService $productService)
    {
        parent::__construct();

        $this->productService = $productService;

        $this->amqpService = $amqpService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch:products-truncated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch for product truncation';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $callback = function (AMQPMessage $message) {
            $this->productService->truncate();
            $message->ack();
        };

        $this->amqpService->consume(
            queue: 'products.truncated',
            callback: $callback,
            exchange: 'catalog_exchange',
            routingKey: 'products.truncated'
        );
    }
}
