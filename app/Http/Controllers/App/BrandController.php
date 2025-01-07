<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BlogFilterRequest;
use App\Http\Requests\Api\BrandFilterRequest;
use App\Models\Blog;
use App\Models\Brand;
use App\Traits\ResponseTrait;

class BrandController extends Controller
{
    use ResponseTrait;

    public function index(BrandFilterRequest $request)
    {
        $result = Brand::filter($request)->get();
        return $this->responseSuccess($result, "Brand List");
    }

    public function show(Brand $brand)
    {
        $brand->load(["feature_image", "cover_image"]);
        return $this->responseSuccess($brand, "Success");
    }
}
