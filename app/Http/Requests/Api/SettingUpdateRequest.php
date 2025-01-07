<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class SettingUpdateRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            "logo" => ["required", "numeric"],
            "name" => ["required", "string"],
            "description" => ["nullable", "string", "min:10"],
            "opening_hours" => ["nullable", "string", "min:10"],
            "email" => ["required", "email"],
            "address1" => ["required", "string"],
            "address2" => ["nullable", "string"],
            "phone1" => ["required", "regex:/(\+977)?[9][6-9]\d{8}/"],
            "phone2" => ["nullable", "regex:/(\+977)?[9][6-9]\d{8}/"],
            "x_link" => ["nullable", "url"],
            "fb_link" => ["nullable", "url"],
            "insta_link" => ["nullable", "url"],
            "youtube_link" => ["nullable", "url"],
            "tiktok_link" => ["nullable", "url"],
            "map_link" => ["nullable", "url"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
