<?php

namespace App\Models;

class Payment
{
    public function __construct(
        private string $firstname,
        private string $lastname,
        // TODO : Switch to carbon
        private string $paymentDate,
        private Amount $amount,
        private string $description,
        // TODO : Switch to value object
        private string $refId
    ) {}

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getPaymentDate(): string
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