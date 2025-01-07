<?php

namespace App\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_ids' => 'required|array|max:2|min:1',
            'address_ids*' => 'required|numeric',
        ];
    }
}
