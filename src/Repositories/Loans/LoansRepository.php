<?php

namespace App\Repositories\Loans;

use App\Models\Loan;
use App\Models\ValueObjects\LoanNumber;

interface LoansRepository
{
    public function getByLoanNumber(LoanNumber|string $loanNumber): ?Loan;

    public function sync(Loan $loan): bool;
}
