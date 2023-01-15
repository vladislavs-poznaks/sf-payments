<?php

namespace App\Models\ValueObjects;

use InvalidArgumentException;

class Amount
{
    private int $amount;

    public function __construct(int $amount)
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Amount must be positive');
        }

        $this->amount = $amount;
    }

    public function lt(Amount $amount): bool
    {
        return $this->amount < $amount->getAmount();
    }

    public function subtract(Amount $amount): Amount
    {
        return new self($this->amount - $amount->getAmount());
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