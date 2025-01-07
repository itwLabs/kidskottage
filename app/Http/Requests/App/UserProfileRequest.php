<?php

namespace App\Http\Requests;

class UserProfileRequest extends ApiFormRequest
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
            'name' => 'string|max:50',
            'phone' => 'string',
            'password' => 'min:6|confirmed',
            'feature_image' => 'nullable|numeric',
        ];
    }
}
