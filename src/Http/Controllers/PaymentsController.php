<?php

namespace App\Http\Controllers;

use App\Http\HttpCode;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Resources\PaymentResource;
use App\Loggers\Logger;
use App\Models\Payment;
use App\Services\Exceptions\PaymentServiceException;
use App\Services\PaymentService;

class PaymentsController
{
    public function __construct(
        private PaymentService $service,
        private Logger $logger
    ) {
    }

    public function store(PaymentStoreRequest $request)
    {
        try {
            $this->service->handle(Payment::make($request->dto()));
        } catch (PaymentServiceException $e) {
            $this->logger::error($e, 'payments');
        }

        return PaymentResource::make($this->service->getPayment(), HttpCode::CREATED);
    }
}
