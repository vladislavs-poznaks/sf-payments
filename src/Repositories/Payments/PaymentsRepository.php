<?php

namespace App\Repositories\Payments;

use App\Models\Collections\PaymentsCollection;
use App\Models\Payment;
use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;

interface PaymentsRepository
{
    public function getByRefId(UuidInterface|string $refId): ?Payment;

    public function getByDate(Carbon $date): PaymentsCollection;

    public function persist(Payment $payment): void;

    public function sync(Payment $payment): bool;

    public function persistAndSync(Payment $payment): bool;
}
