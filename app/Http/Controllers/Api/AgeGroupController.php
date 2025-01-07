<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AgeGroupUpdateRequest;
use App\Models\AgeGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AgeGroupRequest;
use App\Http\Requests\Api\AgeGroupFilterRequest;
use App\Traits\ResponseTrait;

class AgeGroupController extends Controller
{
    use ResponseTrait;

    public function index(AgeGroupFilterRequest $request)
    {
        $result = AgeGroup::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "AgeGroup List");
    }


    public function store(AgeGroupRequest $request)
    {
        $agegroup = AgeGroup::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $agegroup->id], "AgeGroup Created");
    }

    public function show(AgeGroup $agegroup)
    {
        $agegroup->load(["feature_image"]);
        return $this->responseSuccess($agegroup, "Success");
    }

    public function update(AgeGroupUpdateRequest $request, AgeGroup $agegroup)
    {
        $agegroup->update($request->safe()->all());
        return $this->responseSuccess(["id" => $agegroup->id], "AgeGroup Updated");
    }

    public function destroy(AgeGroup $agegroup)
    {
        $agegroup->delete();
        return $this->responseSuccess([], "AgeGroup Deleted");
    }
}
