<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;

class AgeGroupRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string"],
            "feature_image" => ["required", "numeric"],
            "slug" => ["required", "string", "unique:age_groups,slug", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "description" => ["nullable", "string", "min:10"],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
