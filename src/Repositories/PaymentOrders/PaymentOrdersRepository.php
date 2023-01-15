<?php

namespace App\Repositories\PaymentOrders;

use App\Models\PaymentOrder;

interface PaymentOrdersRepository
{
    public function persist(PaymentOrder $order): void;

    public function sync(PaymentOrder $order): bool;
}
