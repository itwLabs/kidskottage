<?php

namespace App\Http\Requests\App;

use App\Http\Requests\ApiFormRequest;

class CartRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ["required", "numeric"],
            'attr_id' => ["nullable", "numeric"],
            'qty' => ["nullable", "numeric"]
        ];
    }
}
