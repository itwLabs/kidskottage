<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;

class ProductUpdateRequest extends ApiFormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string"],
            "slug" => ["required", "string", "unique:products,slug,{$this->product->id},id", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "short_name" => ["required", "string"],
            "keywords" => ["required", "string"],
            "feature_image" => ["nullable", "numeric"],
            "category.*" => ["integer"],
            "category" => ["required", "array"],
            "agegroup.*" => ["integer"],
            "agegroup" => ["required", "array"],
            "brand_id" => ["integer"],
            "gender" => ["string"],
            "attr" => ["array"],
            "price" => ["required", "numeric"],
            "discount" => ["required", "numeric"],
            "stock" => ["required", "numeric"],
            "description" => ["nullable", "string", "min:10"],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
