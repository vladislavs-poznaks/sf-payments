<?php

namespace App\Http\Resources;

use App\Http\HttpCode;
use App\Http\Response;
use App\Models\Payment;

class PaymentResource extends Response
{
    public function __construct(private Payment $payment) {}

    public static function make(Payment $payment, HttpCode $httpCode = HttpCode::OK): string
    {
        $resource = new self($payment);

        return self::json([
            'id' => $resource->payment->getId()->toString(),
            'loanId' => $resource->payment->getLoanId()?->toString(),
            'firstname' => $resource->payment->getFirstname(),
            'lastname' => $resource->payment->getLastname(),
            'paymentDate' => $resource->payment->getPaymentDate()->format('c'),
            'amount' => (string) $resource->payment->getAmount(),
            'description' => $resource->payment->getDescription(),
            'refId' => $resource->payment->getRefId()->toString(),
            'status' => $resource->payment->getStatus()->toString(),
        ], $httpCode);
    }
}