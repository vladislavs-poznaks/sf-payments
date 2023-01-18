<?php

namespace Tests\Traits;

use App\Models\Payment;
use App\Models\ValueObjects\Amount;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait DummyPayments
{
    use DummyLoans;

    protected function createPayment(
        ?UuidInterface $id = null,
        ?UuidInterface $loanId = null,
        string $firstname = 'Test first',
        string $lastname = 'Test last',
        string $description = 'Test description',
        ?Amount $amount = null,
        ?Carbon $paymentDate = null,
        ?UuidInterface $refId = null,
    ): Payment {
        if (is_null($id)) {
            $id = Uuid::uuid4();
        }

        if (is_null($refId)) {
            $refId = Uuid::uuid4();
        }

        if (is_null($amount)) {
            $amount = Amount::make(1000);
        }

        if (is_null($paymentDate)) {
            $paymentDate = Carbon::now();
        }

        $payment = new Payment(
            firstname: $firstname,
            lastname: $lastname,
            paymentDate: $paymentDate,
            amount: $amount,
            description: $description,
            refId: $refId
        );

        $payment->setId($id);

        if ($loanId) {
            $payment->setLoan($this->createLoan($loanId));
        }

        return $payment;
    }
}