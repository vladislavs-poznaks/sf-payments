<?php

namespace Tests\FakeRepositories;

use App\Models\Loan;
use App\Models\ValueObjects\LoanNumber;
use App\Repositories\Loans\LoansRepository;

class FakeLoansRepository implements LoansRepository
{
    protected ?Loan $loan = null;
    protected ?Loan $syncedLoan = null;

    protected array $methodCalls = [
        'getByLoanNumber' => 0,
        'sync' => 0,
    ];

    public function getMethodCalls(): array
    {
        return $this->methodCalls;
    }

    public function getSyncedLoan(): Loan
    {
        return $this->syncedLoan;
    }

    public function setReturnLoan(?Loan $loan = null): self
    {
        $this->loan = $loan;

        return $this;
    }

    public function calledMethod(string $methodName): void
    {
        $this->methodCalls[$methodName]++;
    }

    public function getByLoanNumber(LoanNumber|string $loanNumber): ?Loan
    {
        $this->calledMethod('getByLoanNumber');
        return $this->loan;
    }

    public function sync(Loan $loan): bool
    {
        $this->calledMethod('sync');

        $this->syncedLoan = $loan;

        return true;
    }
}