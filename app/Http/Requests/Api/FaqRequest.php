<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;

class FaqRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "question" => ["required", "string", "min:5"],
            "answer" => ["required", "string", "min:10"],
            // "on_footer" => ["required", Rule::in([0, 1])],
            "isActive" => ["required", Rule::in([0, 1])],
            // "faq_group_id" => ["numeric", "exists:faq_groups,id"],
        ];
    }
}
