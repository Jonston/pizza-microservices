<?php

namespace App\Enums;

enum OrderEventEnum
{
    case ORDER_CREATED;
    case ORDER_PROCESSED;
    case ORDER_DELIVERED;
    case ORDER_COMPLETED;
    case ORDER_CANCELLED;
    case ORDER_FAILED;

    public function getEventName(): string
    {
        return match ($this) {
            self::ORDER_CREATED => 'order.created',
            self::ORDER_PROCESSED => 'order.processed',
            self::ORDER_DELIVERED => 'order.delivered',
            self::ORDER_COMPLETED => 'order.completed',
            self::ORDER_CANCELLED => 'order.cancelled',
            self::ORDER_FAILED => 'order.failed',
        };
    }
}
