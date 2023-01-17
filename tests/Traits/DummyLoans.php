<?php

namespace Tests\Traits;

use App\Models\Loan;
use App\Models\ValueObjects\Amount;
use App\Models\ValueObjects\LoanNumber;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait DummyLoans
{
    protected function createLoan(
        ?UuidInterface $id = null,
        ?UuidInterface $customerId = null,
        ?LoanNumber $loanNumber = null,
        string $state = 'ACTIVE',
        ?Amount $amountIssued = null,
        ?Amount $amountOwed = null,
    ): Loan {
        if (is_null($id)) {
            $id = Uuid::uuid4();
        }

        if (is_null($customerId)) {
            $customerId = Uuid::uuid4();
        }

        if (is_null($loanNumber)) {
            $loanNumber = LoanNumber::make('LN12345678');
        }

        if (is_null($amountIssued)) {
            $amountIssued = Amount::make(random_int(1000, 150000));
        }
        if (is_null($amountOwed)) {
            $interest = floor($amountIssued->getAmount() / 5);
            $amountOwed = Amount::make($amountIssued->getAmount() + $interest);
        }

        $loan = new Loan(
            customerId: $customerId,
            loanNumber: $loanNumber,
            state: $state,
            amountIssued: $amountIssued,
            amountOwed: $amountOwed,
        );

        $loan->setId($id);

        return $loan;
    }
}