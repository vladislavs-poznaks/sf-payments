<?php

namespace App\Models;

enum PaymentStatus: int
{
    case RECEIVED = 101;

    case MISSING_LOAN_NUMBER_ERROR = 201;

    case MULTIPLE_LOAN_NUMBER_ERROR = 202;

    case INCORRECT_LOAN_NUMBER_ERROR = 203;

    case ASSIGNED = 301;

    case PROCESSED = 401;

    public function toString(): string
    {
        return match ($this) {
            self::RECEIVED => 'RECEIVED',
            self::MISSING_LOAN_NUMBER_ERROR => 'MISSING LOAN NUMBER',
            self::MULTIPLE_LOAN_NUMBER_ERROR => 'MULTIPLE LOAN NUMBERS',
            self::INCORRECT_LOAN_NUMBER_ERROR => 'INCORRECT LOAN NUMBER',
            self::ASSIGNED => 'ASSIGNED',
            self::PROCESSED => 'PROCESSED',
        };
    }
}