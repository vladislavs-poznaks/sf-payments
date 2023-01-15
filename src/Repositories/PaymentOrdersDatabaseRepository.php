<?php

namespace App\Repositories;

use App\Models\PaymentOrder;
use Doctrine\ORM\Exception\ORMException;

class PaymentOrdersDatabaseRepository extends DatabaseRepository
{
    public function persist(PaymentOrder $order)
    {
        $this->entityManager->persist($order);
    }

    public function sync(PaymentOrder $order): bool
    {
        try {
            $this->entityManager->flush($order);
            return true;
        } catch (ORMException) {
            return false;
        }
    }
}
