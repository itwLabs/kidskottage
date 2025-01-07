<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class ReviewUpdateRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
