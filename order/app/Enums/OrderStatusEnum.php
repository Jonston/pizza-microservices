<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case DELIVERING = 'delivering';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::DELIVERING => 'Delivering',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::FAILED => 'Failed',
        };
    }
}
