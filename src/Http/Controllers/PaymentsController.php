<?php

namespace App\Http\Controllers;

use App\Dtos\Payments\PaymentDTO;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Response;
use App\Models\Payment;
use App\Models\ValueObjects\Amount;
use App\Repositories\Payments\PaymentsRepository;
use App\Services\Exceptions\PaymentServiceException;
use App\Services\PaymentService;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class PaymentsController
{
    public function __construct(
        private PaymentsRepository $repository,
        private PaymentService     $service
    ) {}

    public function store(PaymentStoreRequest $request)
    {
        $attributes = $request->all();

        if (!$request->isValid()) {
            return Response::json($request->errors(), Response::HTTP_BAD_REQUEST);
        }

        if (!is_null($this->repository->getByRefId($attributes['refId']))) {
            return Response::json([
                'message' => "Payment with refId {$attributes['refId']} already exists"
            ], Response::HTTP_CONFLICT);
        }

        $dto = new PaymentDTO(
            firstName: $attributes['firstname'],
            lastName: $attributes['lastname'],
            description: $attributes['description'],
            amount: Amount::make($attributes['amount'] * 100),
            paymentDate: Carbon::createFromFormat('c', $attributes['paymentDate']),
            refId: Uuid::fromString($attributes['refId'])
        );

        $payment = Payment::make($dto);

        try {
            $this->service->handle($payment);
        } catch (PaymentServiceException $e) {
            return Response::json([
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], Response::HTTP_INTERNAL_ERROR);
        }

        return Response::json($this->service->getPayment()->toArray(), Response::HTTP_CREATED);
    }
}
