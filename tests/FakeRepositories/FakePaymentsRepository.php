<?php

namespace Tests\FakeRepositories;

use App\Models\Collections\PaymentsCollection;
use App\Models\Payment;
use App\Repositories\Payments\PaymentsRepository;
use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;

class FakePaymentsRepository implements PaymentsRepository
{
    protected ?Payment $payment = null;

    protected array $methodCalls = [
        'persist' => 0,
        'sync' => 0,
    ];

    public function getMethodCalls(): array
    {
        return $this->methodCalls;
    }

    public function setReturnPayment(Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function calledMethod(string $methodName): void
    {
        $this->methodCalls[$methodName]++;
    }

    public function getByRefId(UuidInterface|string $refId): ?Payment
    {
        // TODO: Implement getByRefId() method.
    }

    public function getByDate(Carbon $date): PaymentsCollection
    {
        // TODO: Implement getByDate() method.
    }

    public function persist(Payment $payment): void
    {
        $this->calledMethod('persist');
    }

    public function sync(Payment $payment): bool
    {
        $this->calledMethod('sync');

        return true;
    }

    public function persistAndSync(Payment $payment): bool
    {
        // TODO: Implement persistAndSync() method.
    }
}
