<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;

class CategoryFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['string'],
            'feature_image' => ['boolean'],
            'parent_id' => ["numeric"],
            'on_trending' => ["numeric"],
            'feature_no' => ["numeric"],
            'products' => ['boolean'],
            'parent' => ['boolean'],
            'childs' => ['boolean'],
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
