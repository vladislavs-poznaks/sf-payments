<?php

namespace App\Http\Requests;

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
        ];
    }
}