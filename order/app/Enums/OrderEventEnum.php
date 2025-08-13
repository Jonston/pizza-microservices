<?php

namespace App\Enums;

enum OrderEventEnum: string
{
    case ORDER_CREATED = 'order.created';
    case ORDER_PROCESSED = 'order.processed';
    case ORDER_DELIVERED = 'order.delivered';
    case ORDER_COMPLETED = 'order.completed';
    case ORDER_CANCELLED = 'order.cancelled';
    case ORDER_FAILED = 'order.failed';

    public function getEventName(): string
    {
        return match ($this) {
            self::ORDER_CREATED => 'Order Created',
            self::ORDER_PROCESSED => 'Order Processed',
            self::ORDER_DELIVERED => 'Order Delivered',
            self::ORDER_COMPLETED => 'Order Completed',
            self::ORDER_CANCELLED => 'Order Cancelled',
            self::ORDER_FAILED => 'Order Failed',
        };
    }
}
