<?php

namespace App\Repositories\Payments;

use App\Models\Collections\PaymentsCollection;
use App\Models\Payment;
use App\Repositories\DatabaseRepository;
use Carbon\Carbon;
use Doctrine\ORM\Exception\ORMException;
use Ramsey\Uuid\UuidInterface;

class PaymentsDatabaseRepository extends DatabaseRepository implements PaymentsRepository
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

    public function getByDate(Carbon $date): PaymentsCollection
    {
        $query = $this->entityManager->createQueryBuilder();

        $payments = $query
            ->select('p')
            ->from(Payment::class, 'p')
            ->where('p.paymentDate LIKE :date')
            ->setParameter('date', $date->format('Y-m-d') . '%')
            ->getQuery()
            ->getResult();

        return new PaymentsCollection($payments);
    }

    public function persist(Payment $payment): void
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

    public function persistAndSync(Payment $payment): bool
    {
        $this->persist($payment);

        return $this->sync($payment);
    }
}
