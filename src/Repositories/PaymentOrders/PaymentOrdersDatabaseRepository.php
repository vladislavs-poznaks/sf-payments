<?php

namespace App\Repositories\PaymentOrders;

use App\Models\PaymentOrder;
use App\Repositories\DatabaseRepository;
use Doctrine\ORM\Exception\ORMException;

class PaymentOrdersDatabaseRepository extends DatabaseRepository implements PaymentOrdersRepository
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
