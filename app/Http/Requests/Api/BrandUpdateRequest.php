<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;

class BrandUpdateRequest extends ApiFormRequest
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
            "slug" => ["required", "string", "unique:brands,slug,{$this->brand->id},id", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "order" => ["numeric"],
            "description" => ["nullable", "string", "min:10"],
            // "on_footer" => ["required", Rule::in([0, 1])],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
