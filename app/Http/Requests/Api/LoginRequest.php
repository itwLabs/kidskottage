<?php

namespace App\Http\Api\Requests;

use App\Http\Requests\ApiFormRequest;

class LoginRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email" => ["required", "email", "max:100"],
            "password" => ["required", "min:6"]
        ];
    }
}
