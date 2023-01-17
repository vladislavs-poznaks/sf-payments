<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Http\Resources\PaymentResource;
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

    public function store()
    {
        $attributes = Request::getInstance()->all();

        $validator = new Validator($attributes);

        $validator->rules([
            'required' => ['firstname', 'lastname', 'paymentDate', 'amount', 'description', 'refId'],
            'numeric' => ['amount'],
            'min' => [
                ['amount', 0.01]
            ],
            'paymentDateFormat' => ['paymentDate'],
        ]);

        if (!$validator->validate()) {
            return Response::json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        if (!is_null($this->repository->getByRefId($attributes['refId']))) {
            return Response::json([
                'message' => "Payment with refId {$attributes['refId']} already exists"
            ], Response::HTTP_CONFLICT);
        }

        try {
            $this->service->handle(Payment::make($attributes));
        } catch (PaymentServiceException $e) {
            return Response::json([
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], Response::HTTP_INTERNAL_ERROR);
        }

        return PaymentResource::make($this->service->getPayment(), Response::HTTP_CREATED);
    }
}
