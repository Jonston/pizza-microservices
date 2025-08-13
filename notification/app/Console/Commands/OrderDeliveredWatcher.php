<?php

namespace App\Console\Commands;

use App\Events\OrderCreated;
use App\Events\OrderDelivered;
use App\Traits\AMQPMessageValidator;
use Illuminate\Console\Command;
use Jonston\AmqpLaravel\AMQPService;
use PhpAmqpLib\Message\AMQPMessage;

class OrderDeliveredWatcher extends Command
{
    use AMQPMessageValidator;

    protected AMQPService $amqpService;

    public function __construct(AMQPService $amqpService)
    {
        parent::__construct();

        $this->amqpService = $amqpService;
    }

    protected $signature = 'watch:order.delivered';

    protected $description = 'Consume messages from the order exchange';

    public function handle():  void
    {
        $callback = function (AMQPMessage $message) {
            $this->validateOrderMessage($message);

            $data = json_decode($message->getBody(), true);

            $event = new OrderDelivered($data);

            broadcast($event);
        };

        $this->amqpService->consume(
            queue: 'notification.order.delivered',
            callback: $callback,
            exchange: 'order_exchange',
            routingKey: 'order.delivered',
        );
    }
}
