<?php

namespace App\Console\Commands;

use App\Enums\OrderEventEnum;
use App\Exceptions\InvalidMessageException;
use App\Services\OrderService;
use App\Traits\AMQPMessageValidator;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Jonston\AmqpLaravel\AMQPService;
use PhpAmqpLib\Message\AMQPMessage;

class OrderDeliveredWatcher extends Command
{
    use AMQPMessageValidator;

    protected OrderService $orderService;

    protected AMQPService $amqpService;

    protected $signature = 'watch:order.delivered';

    protected $description = 'Consume messages from order_exchange';

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
        $callback = function (AMQPMessage $message) {
            $this->processMessage($message);
        };

        $this->amqpService->consume(
            queue: 'order.order.delivered',
            callback: $callback,
            exchange: 'order_exchange',
            routingKey: 'order.delivered',
        );
    }

    /**
     * @throws InvalidMessageException
     */
    private function processMessage(AMQPMessage $message): void
    {
        $this->validateOrderMessage($message);

        $data = json_decode($message->getBody(), true);

        $this->orderService->complete($data['order']['id']);
    }
}