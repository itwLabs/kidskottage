<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SliderRequest;
use App\Http\Requests\Api\SliderUpdateRequest;
use App\Http\Requests\Api\SliderFilterRequest;
use App\Models\Slider;
use App\Traits\ResponseTrait;

class SliderController extends Controller
{
    use ResponseTrait;

    public function index(SliderFilterRequest $request)
    {
        $result = Slider::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "Slider List");
    }


    public function store(SliderRequest $request)
    {
        $slider = Slider::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $slider->id], "Slider Created");
    }

    public function show(Slider $slider)
    {
        $slider->load(["feature_image"]);
        return $this->responseSuccess($slider, "Success");
    }

    public function update(SliderUpdateRequest $request, Slider $slider)
    {
        $slider->update($request->safe()->toArray());
        return $this->responseSuccess(["id" => $slider->id], "Slider Updated");
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();
        return $this->responseSuccess([], "Slider Deleted");
    }
}
