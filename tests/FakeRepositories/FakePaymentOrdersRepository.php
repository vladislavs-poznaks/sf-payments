<?php

namespace Tests\FakeRepositories;

use App\Models\Payment;
use App\Models\PaymentOrder;
use App\Repositories\PaymentOrders\PaymentOrdersRepository;
use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;

class FakePaymentOrdersRepository implements PaymentOrdersRepository
{
    protected ?PaymentOrder $paymentOrder = null;
    protected ?PaymentOrder $syncedPaymentOrder = null;


    protected array $methodCalls = [
        'persist' => 0,
        'sync' => 0,
    ];

    public function getMethodCalls(): array
    {
        return $this->methodCalls;
    }

    public function getSyncedPaymentOrder(): PaymentOrder
    {
        return $this->syncedPaymentOrder;
    }

    public function setReturnPaymentOrder(PaymentOrder $paymentOrder): self
    {
        $this->paymentOrder = $paymentOrder;

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

    public function getByDate(Carbon $date): array
    {
        // TODO: Implement getByDate() method.
    }

    public function persist(PaymentOrder $paymentOrder): void
    {
        $this->calledMethod('persist');
    }

    public function sync(PaymentOrder $paymentOrder): bool
    {
        $this->calledMethod('sync');

        $this->syncedPaymentOrder = $paymentOrder;

        return true;
    }
}
