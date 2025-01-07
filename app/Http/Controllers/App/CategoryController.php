<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BlogFilterRequest;
use App\Http\Requests\Api\CategoryFilterRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Traits\ResponseTrait;

class CategoryController extends Controller
{
    use ResponseTrait;

    public function index(CategoryFilterRequest $request)
    {
        $result = Category::filter($request)->get();
        return $this->responseSuccess($result, "Category List");
    }

    public function show(Category $category)
    {
        $category->load(["feature_image", "cover_image"]);
        return $this->responseSuccess($category, "Success");
    }
}
