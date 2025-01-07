<?php

namespace App\Http\Requests\Api;

use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class BlogRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "feature_image" => ["required", "numeric"],
            "cover_image" => ["required", "numeric"],
            "name" => ["required", "string", "min:5"],
            "slug" => ["required", "string", "unique:blogs,slug", "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/"],
            "short_name" => ["required", "string", "min:5"],
            "author" => ["required", "string"],
            // "on_footer" => ["required", Rule::in([0, 1])],
            "publish_date" => ["nullable", "date"],
            "description" => ["required", "string", "min:10"],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
