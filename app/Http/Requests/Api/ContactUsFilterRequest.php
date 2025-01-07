<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;

class ContactUsFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['string'],
            'email' => ['string'],
            'phone' => ['string'],
            'subject' => ['string'],
            'message' => ['string'],
            'isActive' => ['array']
        ];
    }

    public function all($keys = null)
    {
        return parent::all($keys) + $this->query->all();
    }
}
