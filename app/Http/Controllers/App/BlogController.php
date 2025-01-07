<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BlogFilterRequest;
use App\Models\Blog;
use App\Traits\ResponseTrait;

class BlogController extends Controller
{
    use ResponseTrait;

    public function index(BlogFilterRequest $request)
    {
        $result = Blog::filter($request)->get();
        return $this->responseSuccess($result, "Blog List");
    }

    public function show(Blog $blog)
    {
        $blog->load(["feature_image", "cover_image"]);
        return $this->responseSuccess($blog, "Success");
    }
}
