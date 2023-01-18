<?php

namespace App\Http\Controllers;

use App\Http\HttpCode;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Response;
use App\Models\Payment;
use App\Services\Exceptions\PaymentServiceException;
use App\Services\PaymentService;
use App\Dtos\Payments\PaymentDTO;
use App\Models\ValueObjects\Amount;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class PaymentsController
{
    public function __construct(
        private PaymentService $service
    ) {}

    public function store(PaymentStoreRequest $request)
    {
        $attributes = $request->all();

        $dto = new PaymentDTO(
            firstName: $attributes['firstname'],
            lastName: $attributes['lastname'],
            description: $attributes['description'],
            amount: Amount::make($attributes['amount'] * 100),
            paymentDate: Carbon::createFromFormat('c', $attributes['paymentDate']),
            refId: Uuid::fromString($attributes['refId'])
        );

        try {
            $this->service->handle(Payment::make($dto));
        } catch (PaymentServiceException $e) {
            // Log payment processing error
        }

        return Response::json($this->service->getPayment()->toArray(), HttpCode::CREATED);
    }
}
