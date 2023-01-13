<?php

namespace App\Models;

use http\Exception\InvalidArgumentException;

class Amount
{
    private int $amount;

    public function __construct(int $amount)
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive');
        }

        $this->amount = $amount;
    }

    public static function make(int $amount): self
    {
        return new self($amount);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}