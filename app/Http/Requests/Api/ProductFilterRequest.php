<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ids' => ['array'],
            'name' => ['string'],
            'attr' => ['boolean'],
            'brand_id' => ['numeric'],
            'brand' => ['boolean'],
            'agegroup' => ['boolean'],
            'agegroup_id' => ['number'],
            'gallery' => ['boolean'],
            'category' => ['boolean'],
            'category_id' => ['numeric'],
            'isActive' => ['array']
        ];
    }

    public function where()
    {
        return [
            "ids" => function ($query, $operator, $value) {
                $query->whereIn('products.id', $value);
            },
            "category_id" => function ($query, $operator, $value) {
                $query->whereHas('category', function ($query) use ($operator, $value) {
                    if ($operator == "in") {
                        $query->whereIn('category.id', $value);
                    } else {
                        $query->where('category.id', $operator, $value);
                    }
                });
            },
            "agegroup_id" => function ($query, $operator, $value) {
                $query->whereHas('agegroup', function ($query) use ($operator, $value) {
                    if ($operator == "in") {
                        $query->whereIn('agegroup.id', $value);
                    } else {
                        $query->where('agegroup.id', $operator, $value);
                    }
                });
            }
        ];
    }

    public function all($keys = null)
    {
        return parent::all($keys) + $this->query->all();
    }
}
