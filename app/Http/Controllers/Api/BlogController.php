<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BlogRequest;
use App\Http\Requests\Api\BlogUpdateRequest;
use App\Http\Requests\Api\BlogFilterRequest;
use App\Models\Blog;
use App\Traits\ResponseTrait;

class BlogController extends Controller
{
    use ResponseTrait;

    public function index(BlogFilterRequest $request)
    {
        $result = Blog::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "Blog List");
    }


    public function store(BlogRequest $request)
    {
        $blog = Blog::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $blog->id], "Blog Created");
    }

    public function show(Blog $blog)
    {
        $blog->load(["feature_image", "cover_image"]);
        return $this->responseSuccess($blog, "Success");
    }

    public function update(BlogUpdateRequest $request, Blog $blog)
    {
        $blog->update($request->safe()->toArray());
        return $this->responseSuccess(["id" => $blog->id], "Blog Updated");
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return $this->responseSuccess([], "Blog Deleted");
    }
}
