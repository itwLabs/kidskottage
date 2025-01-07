<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;
use App\Enums\SectionType;
use Illuminate\Validation\Rule;

class SectionFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['string'],
            'type' => [Rule::in(array_column(SectionType::cases(), 'value'))],
            'isActive' => ['array']
        ];
    }

    // public function all($keys = null)
    // {
    //     // Merge query parameters into request data
    //     return array_merge(parent::all($keys), $this->query->all());
    // }
}
