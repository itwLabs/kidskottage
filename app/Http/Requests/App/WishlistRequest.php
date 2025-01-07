<?php

namespace App\Http\Requests\App;

use App\Http\Requests\ApiFormRequest;

class WishlistRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer'],
            'attr_id' => ['integer'],
        ];
    }
}
