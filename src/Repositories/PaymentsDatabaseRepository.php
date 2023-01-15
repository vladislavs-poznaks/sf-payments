<?php

namespace App\Repositories;

use App\Models\Payment;
use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;

class PaymentsDatabaseRepository extends DatabaseRepository
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

    public function store(Payment $payment)
    {
        $this->entityManager->persist($payment);

        $this->entityManager->flush();

        $this->entityManager->refresh($payment);

        return $payment;
    }
}