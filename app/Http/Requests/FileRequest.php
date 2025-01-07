<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiFormRequest;

class FileRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:png,jpg,jpeg,svg,gif,webp,pdf,doc,docx,xlsx,xls,pdf|max:2048',
            'alt' => 'nullable|string|min:5',
        ];
    }
}
