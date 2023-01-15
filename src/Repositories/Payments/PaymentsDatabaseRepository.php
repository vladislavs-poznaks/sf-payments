<?php

namespace App\Repositories;

use App\Models\Payment;
use Carbon\Carbon;
use Doctrine\ORM\Exception\ORMException;
use Ramsey\Uuid\UuidInterface;

class PaymentsDatabaseRepository extends DatabaseRepository implements PaymentRepository
{
    public function getByRefId(UuidInterface|string $refId): ?Payment
    {
        $query = $this->entityManager->createQueryBuilder();

        return $query
            ->select('p')
            ->from(Payment::class, 'p')
            ->where('p.refId = :refId')
            ->setParameter('refId', $refId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getByDate(Carbon $date)
    {
        $query = $this->entityManager->createQueryBuilder();

        return $query
            ->select('p')
            ->from(Payment::class, 'p')
            ->where('p.paymentDate LIKE :date')
            ->setParameter('date', $date->format('Y-m-d') . '%')
            ->getQuery()
            ->getResult();
    }

    public function persist(Payment $payment)
    {
        $this->entityManager->persist($payment);
    }

    public function sync(Payment $payment): bool
    {
        try {
            $this->entityManager->flush($payment);
            return true;
        } catch (ORMException) {
            return false;
        }
    }
}
