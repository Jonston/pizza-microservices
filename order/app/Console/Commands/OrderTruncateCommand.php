<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Illuminate\Console\Command;

class OrderTruncateCommand extends Command
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();

        $this->orderService = $orderService;
    }

    protected $signature = 'order:truncate';

    protected $description = 'Truncate the orders table';

    public function handle(): int
    {
        try {
            $this->orderService->truncate();
            $this->info('Orders table truncated successfully.');
        } catch (\Exception $e) {
            $this->error('Failed to truncate orders table: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
