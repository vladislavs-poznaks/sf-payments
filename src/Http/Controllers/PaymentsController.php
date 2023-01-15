<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Models\Payment;
use App\Repositories\PaymentsDatabaseRepository;
use App\Services\Exceptions\PaymentServiceException;
use App\Services\PaymentService;
use Valitron\Validator;

class PaymentsController
{
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

        $repository = new PaymentsDatabaseRepository();

        if (!is_null($repository->getByRefId($attributes['refId']))) {
            return Response::json([
                'message' => "Payment with refId {$attributes['refId']} already exists"
            ], Response::HTTP_CONFLICT);
        }

        $service = new PaymentService();

        try {
            $service->handle(Payment::make($attributes));
        } catch (PaymentServiceException $e) {
            return Response::json([
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], Response::HTTP_INTERNAL_ERROR);
        }

        return Response::json($service->getPayment()->toArray(), Response::HTTP_CREATED);
    }
}
