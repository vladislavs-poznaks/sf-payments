<?php

namespace App\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class Payment
{
    public function __construct(
        private string $firstname,
        private string $lastname,
        private Carbon $paymentDate,
        private Amount $amount,
        private string $description,
        private Uuid $refId
    ) {}

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getPaymentDate(): Carbon
    {
        return $this->paymentDate;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRefId(): string
    {
        return $this->refId;
    }
}