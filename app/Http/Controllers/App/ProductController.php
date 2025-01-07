<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BlogFilterRequest;
use App\Http\Requests\Api\ProductFilterRequest;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Promotion;
use App\Traits\ResponseTrait;

class ProductController extends Controller
{
    use ResponseTrait;

    public function index(ProductFilterRequest $request)
    {
        $result = Product::filter($request)->get();
        return $this->responseSuccess($result, "Product List");
    }

    public function show(Product $product)
    {
        $product->load(["feature_image", "gallery", "category", "agegroup", "brand", "attr"]);
        return $this->responseSuccess($product, "Success");
    }
}
