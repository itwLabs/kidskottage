<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PageFilterRequest;
use App\Http\Requests\Api\PageRequest;
use App\Http\Requests\Api\PageUpdateRequest;
use App\Models\Page;
use App\Traits\ResponseTrait;

class ListingController extends Controller
{
    use ResponseTrait;

    public function index(PageFilterRequest $request)
    {
        $result = Page::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "Page List");
    }

    public function store(PageRequest $request)
    {
        $page = Page::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $page->id], "Page Created");
    }

    public function show(Page $page)
    {
        $page->load(["feature_image"]);
        return $this->responseSuccess($page, "Success");
    }

    public function update(PageUpdateRequest $request, Page $page)
    {
        $page->update($request->safe()->toArray());
        return $this->responseSuccess(["id" => $page->id], "Page Updated");
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return $this->responseSuccess([], "Page Deleted");
    }
}
