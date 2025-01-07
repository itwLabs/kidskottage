<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AgeGroupFilterRequest;
use App\Http\Requests\Api\BrandFilterRequest;
use App\Models\AgeGroup;
use App\Models\Brand;
use App\Traits\ResponseTrait;

class AgegroupController extends Controller
{
    use ResponseTrait;

    public function index(AgeGroupFilterRequest $request)
    {
        $result = AgeGroup::filter($request)->get();
        return $this->responseSuccess($result, "Agegroup List");
    }

    public function show(AgeGroup $ageGroup)
    {
        $ageGroup->load(["feature_image"]);
        return $this->responseSuccess($ageGroup, "Success");
    }
}
