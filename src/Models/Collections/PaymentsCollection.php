<?php

namespace App\Models\Collections;

use App\Models\Payment;

class PaymentsCollection
{
    private array $payments = [];

    public function __construct(array $payments)
    {
        foreach ($payments as $payment) {
            $this->add($payment);
        }
    }

    public function add(Payment $payment): void
    {
        $this->payments[] = $payment;
    }

    public function all(): array
    {
        return $this->payments;
    }

    public static function make(array $payments): PaymentsCollection
    {
        return new self($payments);
    }
}