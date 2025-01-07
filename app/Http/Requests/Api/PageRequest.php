<?php

namespace App\Http\Requests\Api;

use App\Enums\PageType;
use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Status;

class PageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "feature_image" => ["required", "numeric"],
            "name" => ["required", "string", "min:3"],
            "slug" => ["required", "string", "unique:pages,slug", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "type" => ["required", Rule::in(array_column(PageType::cases(), "value"))],
            "description" => ["required", "string", "min:10"],
            "order" => ["numeric"],
            "on_footer" => ["required", Rule::in([0, 1])],
            "isActive" => ["required", Rule::in([0, 1])]
        ];
    }
}
