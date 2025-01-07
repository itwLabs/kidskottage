<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;

class AttributeRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attr' => ["required", "array", "min:1"],
            'attr.*.is_default' => ["required", "boolean"],
            'attr.*.attr' => ["required", "array"],
            'attr.*.price' => ["required", "numeric", "min:0"],
            'attr.*.stock' => ["required", "integer", "min:0"],
        ];
    }
}
