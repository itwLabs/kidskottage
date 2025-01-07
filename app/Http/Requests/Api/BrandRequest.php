<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;

class BrandRequest extends ApiFormRequest
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
            // "cover_image" => ["required", "numeric"],
            "slug" => ["required", "string", "unique:brands,slug", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "description" => ["nullable", "string", "min:10"],
            // "on_footer" => ["required", Rule::in([0, 1])],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
