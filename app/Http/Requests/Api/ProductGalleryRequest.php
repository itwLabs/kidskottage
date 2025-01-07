<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;

class ProductGalleryRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "feature_image" => ["required", "numeric"],
        ];
    }
}
