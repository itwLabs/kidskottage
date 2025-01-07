<?php

namespace App\Http\Requests\Api;

use App\Enums\Status;
use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class CouponUpdateRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "min:5"],
            "code" => ["required", "string", "unique:coupons,code,{$this->coupon->id}"],
            "value" => ["required", "numeric"],
            "type" => ["required", Rule::in(["Percentage", "Amount"])],
            "uses_limit" => ["nullable", "numeric"],
            "ends_on" => ["nullable", "date"],
            "isActive" => ["required", Rule::in([0, 1])],
        ];
    }
}
