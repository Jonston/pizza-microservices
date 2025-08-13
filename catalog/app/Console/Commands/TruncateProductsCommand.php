<?php

namespace App\Console\Commands;

use App\Services\ProductService;
use Illuminate\Console\Command;

class TruncateProductsCommand extends Command
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        parent::__construct();

        $this->productService = $productService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate the products table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->productService->truncate();
            $this->info('Products table truncated successfully.');
        } catch (\Exception $e) {
            $this->error('Failed to truncate products table: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
