<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;

class SaleFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_name' => ['string'],
            'user_email' => ['string'],
            'user_phone' => ['string'],
            'code' => ['string'],
            'status' => ['string'],
            'isActive' => ['array']
        ];
    }

    public function where()
    {
        return [
            "user_name" => function ($query, $operator, $value) {
                $query->whereHas('users', function ($query) use ($operator, $value) {
                    $query->where('users.name', 'like', "%$value%");
                });
            },
            "user_email" => function ($query, $operator, $value) {
                $query->whereHas('users', function ($query) use ($operator, $value) {
                    $query->where('users.email', 'like', "%$value%");
                });
            },
            "user_phone" => function ($query, $operator, $value) {
                $query->whereHas('users', function ($query) use ($operator, $value) {
                    $query->where('users.phone', 'like', "%$value%");
                });
            },
        ];
    }

    public function all($keys = null)
    {
        return parent::all($keys) + $this->query->all();
    }
}
