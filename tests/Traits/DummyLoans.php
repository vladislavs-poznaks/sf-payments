<?php

namespace Tests\Traits;

use App\Models\Loan;
use App\Models\ValueObjects\Amount;
use App\Models\ValueObjects\LoanNumber;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait DummyLoans
{
    protected function createLoanWithId(UuidInterface|string $loanId = null): Loan
    {
        $loan = new Loan(
            customerId: Uuid::uuid4(),
            loanNumber: LoanNumber::make('LN12345678'),
            state: 'ACTIVE',
            amountIssued: Amount::make(1000),
            amountOwed: Amount::make(1200),
        );

        $loan->setId($loanId ?? Uuid::uuid4());

        return $loan;
    }
}