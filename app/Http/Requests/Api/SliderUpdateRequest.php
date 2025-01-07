<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class SliderUpdateRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "feature_image" => ["numeric"],
            "name" => ["required", "string", "min:5"],
            "link" => ["nullable","string", "min:5"],
            "order" => ["numeric"],
            "description" => ["string", "min:10"],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
