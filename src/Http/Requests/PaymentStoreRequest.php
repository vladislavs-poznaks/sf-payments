<?php

namespace App\Http\Requests;

use App\Http\HttpCode;
use App\Http\Request;

class PaymentStoreRequest extends Request
{
    public function rules(): array
    {
        return [
            'required' => ['firstname', 'lastname', 'paymentDate', 'amount', 'description', 'refId'],
            'numeric' => ['amount'],
            'min' => [
                ['amount', 0.01]
            ],
            'paymentDateFormat' => ['paymentDate'],
            'uniqueRefId' => ['refId'],
        ];
    }

    public function getHttpErrorCode(): HttpCode
    {
        if ($this->errors('refId')) {
            return HttpCode::CONFLICT;
        }

        return HttpCode::BAD_REQUEST;
    }
}