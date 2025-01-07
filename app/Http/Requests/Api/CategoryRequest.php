<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "feature_image" => ["required", "numeric"],
            "name" => ["required", "string", "min:3"],
            "slug" => ["required", "string", "unique:category,slug", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "description" => ["nullable", "string", "min:10"],
            "parent_id" => ["nullable", "numeric", "exists:category,id"],
            "feature_no" => ["numeric"],
            // "on_footer" => ["required", Rule::in([0, 1])],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
