<?php

namespace App\Http\Requests\Api;

use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "feature_image" => ["numeric"],
            "name" => ["required", "string", "min:3"],
            "slug" => ["required", "string", "unique:category,slug,{$this->category->id},id", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "description" => ["nullable", "string", "min:10"],
            "parent_id" => ["nullable", "numeric", "exists:category,id"],
            "order" => ["numeric"],
            "feature_no" => ["numeric"],
            // "on_footer" => ["required", Rule::in([0, 1])],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
