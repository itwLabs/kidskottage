<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;

class PaymentFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sale_id' => ['numeric'],
        ];
    }

    public function all($keys = null)
    {
        return parent::all($keys) + $this->query->all();
    }
}
