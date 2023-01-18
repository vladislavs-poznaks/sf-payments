<?php

namespace App\Dtos\Payments;

use App\Models\ValueObjects\Amount;
use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;

class PaymentDTO
{
    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly string $description,
        private readonly Amount $amount,
        private readonly Carbon $paymentDate,
        private readonly UuidInterface $refId,
    ) {}

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getPaymentDate(): Carbon
    {
        return $this->paymentDate;
    }

    public function getRefId(): UuidInterface
    {
        return $this->refId;
    }
}