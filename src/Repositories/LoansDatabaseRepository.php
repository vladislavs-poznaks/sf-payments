<?php

namespace App\Repositories;

use App\Models\Loan;
use App\Models\ValueObjects\LoanNumber;

class LoansDatabaseRepository extends DatabaseRepository
{
    public function getByLoanNumber(LoanNumber|string $loanNumber): ?Loan
    {
        $query = $this->entityManager->createQueryBuilder();

        return $query
            ->select('l')
            ->from(Loan::class, 'l')
            ->where('l.loanNumber = :loanNumber')
            ->setParameter('loanNumber', $loanNumber)
            ->getQuery()
            ->getOneOrNullResult();
    }
}