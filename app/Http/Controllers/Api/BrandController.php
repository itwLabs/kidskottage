<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\BrandUpdateRequest;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BrandRequest;
use App\Http\Requests\Api\BrandFilterRequest;
use App\Traits\ResponseTrait;

class BrandController extends Controller
{
    use ResponseTrait;

    public function index(BrandFilterRequest $request)
    {
        $result = Brand::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "Brand List");
    }


    public function store(BrandRequest $request)
    {
        $brand = Brand::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $brand->id], "Brand Created");
    }

    public function show(Brand $brand)
    {
        $brand->load(["feature_image"]);
        return $this->responseSuccess($brand, "Success");
    }

    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        $brand->update($request->safe()->all());
        return $this->responseSuccess(["id" => $brand->id], "Brand Updated");
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return $this->responseSuccess([], "Brand Deleted");
    }
}
