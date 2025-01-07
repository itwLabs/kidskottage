<?php

namespace App\Http\Requests\App;

use App\Http\Requests\ApiFormRequest;

class ProfileAddressRequest extends ApiFormRequest
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
            'name' => 'required|string|max:50',
            'phone' => 'string|max:15',
            'type' => 'required|string|max:20',
            'email' => 'string|email',
            'address' => 'required|string'
        ];
    }
}
