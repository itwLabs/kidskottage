<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;

class BrandFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['string'],
            'products' => ['boolean'],
            'feature_image' => ['boolean'],
            'isActive' => ['array']
        ];
    }

    public function where()
    {
        return [
            'feature_image' => function ($query) {
                $query->with(["feature_image"]);
            }
        ];
    }

    public function all($keys = null)
    {
        return parent::all($keys) + $this->query->all();
    }
}
