<?php

namespace App\Payments\Dtos;

use App\Models\ValueObjects\Amount;
use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;

interface PaymentDTO
{
    public function getFirstName(): string;

    public function getLastName(): string;

    public function getDescription(): string;

    public function getAmount(): Amount;

    public function getPaymentDate(): Carbon;

    public function getRefId(): UuidInterface;
}