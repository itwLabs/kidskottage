<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class SaleItemUpdateRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|numeric',
            'status' => 'string',
            'qty' => 'numeric'
        ];
    }
}
