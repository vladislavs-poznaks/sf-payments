<?php

namespace App\Models;

enum PaymentOrderStatus: int
{
    case ORDERED = 101;

    case PROCESSED = 401;

    public function toString(): string
    {
        return match ($this) {
            self::ORDERED => 'ORDERED',
            self::PROCESSED => 'PROCESSED',
        };
    }
}