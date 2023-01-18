<?php

namespace App\Repositories\Loans;

use App\Models\Loan;
use App\Models\ValueObjects\LoanNumber;
use App\Repositories\DatabaseRepository;
use Doctrine\ORM\Exception\ORMException;

class LoansDatabaseRepository extends DatabaseRepository implements LoansRepository
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

    public function sync(Loan $loan): bool
    {
        try {
            $this->entityManager->flush($loan);

            return true;
        } catch (ORMException) {
            return false;
        }
    }
}
