<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Response;
use App\Models\Payment;
use App\Repositories\Payments\PaymentsRepository;
use App\Services\Exceptions\PaymentServiceException;
use App\Services\PaymentService;
use Valitron\Validator;

class PaymentsController
{
    public function __construct(
        private PaymentsRepository $repository,
        private PaymentService     $service
    ) {}

    public function store(PaymentStoreRequest $request)
    {
        if (!$request->isValid()) {
            return Response::json($request->errors(), Response::HTTP_BAD_REQUEST);
        }

        if (!is_null($this->repository->getByRefId($request->all()['refId']))) {
            return Response::json([
                'message' => "Payment with refId {$request->all()['refId']} already exists"
            ], Response::HTTP_CONFLICT);
        }

        try {
            $this->service->handle(Payment::make($request->all()));
        } catch (PaymentServiceException $e) {
            return Response::json([
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], Response::HTTP_INTERNAL_ERROR);
        }

        return Response::json($this->service->getPayment()->toArray(), Response::HTTP_CREATED);
    }
}
