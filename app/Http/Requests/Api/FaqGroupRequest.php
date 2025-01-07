<?php

namespace App\Http\Requests\Api;

use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class FaqGroupRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "min:3"],
            "slug" => ["required", "string", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
