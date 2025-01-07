<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SettingUpdateRequest;
use App\Models\Setting;
use App\Traits\ResponseTrait;

class SettingController extends Controller
{
    use ResponseTrait;

    public function show()
    {
        $setting = Setting::query()->with(["logo"])->first();
        return $this->responseSuccess($setting, "Success");
    }

    public function update(SettingUpdateRequest $request)
    {
        $setting = Setting::first();
        $setting->update($request->safe()->toArray());
        return $this->responseSuccess(["id" => $setting->id], "Success");
    }
}
