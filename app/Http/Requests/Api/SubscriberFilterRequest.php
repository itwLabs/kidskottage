<?php

namespace App\Http\Requests\Api;

use App\Abstracts\FormRequest;
use App\Enums\SectionType;
use Illuminate\Validation\Rule;

class SubscriberFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['string'],
        ];
    }

    public function all($keys = null)
    {
        return parent::all($keys) + $this->query->all();
    }
}
