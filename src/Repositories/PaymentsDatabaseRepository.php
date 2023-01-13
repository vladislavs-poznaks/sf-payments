<?php

namespace App\Repositories;

class PaymentsDatabaseRepository extends DatabaseRepository
{
    public function getById(int $id)
    {
        $query = $this->getConnection()->createQueryBuilder();

        return $query
            ->select('id')
            ->from('payments')
            ->where('id = :id')
            ->setParameters([
                'id' => $id
            ])
            ->fetchAssociative();
    }
}