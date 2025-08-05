<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OrderCreatedConsumer extends Command
{
    protected $signature = 'amqp:consume-order-created';

    protected $description = 'Consume order created messages';

    public function handle()
    {
        //
    }
}
