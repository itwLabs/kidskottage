<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class ImageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:png,jpg,jpeg,svg,gif,webp|max:2048',
            'alt' => 'required|string|min:5',
        ];
    }
}
