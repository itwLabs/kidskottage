<?php

namespace App\Http\Requests\App;

use App\Http\Requests\ApiFormRequest;

class ContactUsRequest extends ApiFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['string', 'required'],
            'email' => ['string', 'required'],
            'phone' => ['string'],
            'message' => ['string', 'required'],
        ];
    }
}
