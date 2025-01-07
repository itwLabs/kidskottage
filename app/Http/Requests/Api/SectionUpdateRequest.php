<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Status;

class SectionUpdateRequest extends ApiFormRequest
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
            "data1" => ["string"],
            "data2" => ["string"],
            "data3" => ["string"],
            "data4" => ["string"],
            "order" => ["numeric"],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
