<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;

class ReviewFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_name' => ['string'],
            'product_name' => ['string'],
            'order_id' => ['numeric'],
            'user_id' => ['numeric'],
            'product_id' => ['numeric'],
            'rating' => ['array'],
            'isActive' => ['array']
        ];
    }

    public function all($keys = null)
    {
        return parent::all($keys) + $this->query->all();
    }
}
