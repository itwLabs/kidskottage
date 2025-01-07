<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;

class AgeGroupUpdateRequest extends ApiFormRequest
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
            "slug" => ["required", "string", "unique:age_groups,slug,{$this->agegroup->id},id", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "description" => ["nullable", "string", "min:10"],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
