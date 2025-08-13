<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Models\Order;
use Jonston\AmqpLaravel\AMQPService;

class NotificationService
{
    protected AMQPService $amqpService;

    public function __construct(AMQPService $amqpService)
    {
        $this->amqpService = $amqpService;
    }

    public function sendOrderCreatedNotification(Order $order): void
    {
        try {
            $event = new OrderCreated($order);

            broadcast($event);
        } catch (\Exception $e) {
            logger()->error('Failed to send order created notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            if (app()->environment(['local', 'testing'])) {
                logger()->debug('Stack trace:', ['trace' => $e->getTraceAsString()]);
            }
        }
    }
}