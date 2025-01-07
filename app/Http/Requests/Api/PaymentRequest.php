<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class PaymentRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sale_id' => 'required|numeric',
            'amount' => 'required|numeric|min:1',
        ];
    }
}
