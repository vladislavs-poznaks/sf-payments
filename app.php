<?php

use App\Repositories\Loans\LoansDatabaseRepository;
use App\Repositories\Loans\LoansRepository;
use App\Repositories\PaymentOrders\PaymentOrdersDatabaseRepository;
use App\Repositories\PaymentOrders\PaymentOrdersRepository;
use App\Repositories\Payments\PaymentsRepository;
use App\Repositories\Payments\PaymentsDatabaseRepository;
use function DI\create;

return [
    LoansRepository::class => create(LoansDatabaseRepository::class),
    PaymentsRepository::class => create(PaymentsDatabaseRepository::class),
    PaymentOrdersRepository::class => create(PaymentOrdersDatabaseRepository::class),
];
