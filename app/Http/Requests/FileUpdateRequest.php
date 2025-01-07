<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiFormRequest;

class FileUpdateRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alt' => 'nullable|string|min:5',
            'order' => 'nullable|numeric'
        ];
    }
}
