<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class SaleUpdateRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'state' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }
}
