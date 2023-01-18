<?php

namespace App\Models\ValueObjects;

use App\Models\ValueObjects\Exceptions\NegativeAmountException;

class Amount
{
    private int $amount;

    public function __construct(int $amount)
    {
        if ($amount < 0) {
            throw new NegativeAmountException('Amount cannot be negative');
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

    public function __toString(): string
    {
        return (string) round($this->amount / 100, 2);
    }
}
