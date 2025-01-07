<?php

namespace App\Http\Requests\App;

use App\Http\Requests\ApiFormRequest;

class ReviewRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer'],
            'message' => ['required', 'string'],
            'agree' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'agree.required' => 'You must agree to the policy'
        ];
    }
}
