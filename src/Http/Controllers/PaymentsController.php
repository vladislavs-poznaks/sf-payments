<?php

namespace App\Http\Controllers;

use App\Http\HttpCode;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Response;
use App\Models\Payment;
use App\Services\Exceptions\PaymentServiceException;
use App\Services\PaymentService;

class PaymentsController
{
    public function __construct(
        private PaymentService $service
    ) {}

    public function store(PaymentStoreRequest $request)
    {
        try {
            $this->service->handle(Payment::make($request->dto()));
        } catch (PaymentServiceException $e) {
            // Log payment processing error
        }

        return Response::json($this->service->getPayment()->toArray(), HttpCode::CREATED);
    }
}
