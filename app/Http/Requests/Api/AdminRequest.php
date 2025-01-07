<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;

class AdminRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "feature_image" => ["numeric"],
            "name" => ["required", "string"],
            "email" => ["required", "string", "unique:admins,email"],
            "password" => ["required", "string", "min:5"],
            "isActive" => ["required", Rule::in([0, 1])]
        ];
    }
}
