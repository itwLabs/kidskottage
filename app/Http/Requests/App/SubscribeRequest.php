<?php

namespace App\Http\Requests\App;

use App\Http\Requests\ApiFormRequest;

class SubscribeRequest extends ApiFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['string', 'email', 'required'],
        ];
    }
}
