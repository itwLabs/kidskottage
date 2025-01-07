<?php

namespace App\Http\Requests\App;

use App\Http\Requests\ApiFormRequest;

class ProfileUpdateRequest extends ApiFormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'feature_image' => 'nullable|numeric',
            'name' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:15'
        ];
    }
}
