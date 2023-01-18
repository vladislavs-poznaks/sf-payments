<?php

namespace App\Http\Requests;

use App\Dtos\Payments\PaymentDTO;
use App\Http\HttpCode;
use App\Http\Request;
use App\Models\ValueObjects\Amount;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

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

    public function dto(): PaymentDTO
    {
        $attributes = $this->all();

        return new PaymentDTO(
            firstName: $attributes['firstname'],
            lastName: $attributes['lastname'],
            description: $attributes['description'],
            amount: Amount::make($attributes['amount'] * 100),
            paymentDate: Carbon::createFromFormat('c', $attributes['paymentDate']),
            refId: Uuid::fromString($attributes['refId'])
        );
    }

    public function getHttpErrorCode(): HttpCode
    {
        if ($this->errors('refId')) {
            return HttpCode::CONFLICT;
        }

        return HttpCode::BAD_REQUEST;
    }
}