<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryFilterRequest;
use App\Http\Requests\Api\CategoryRequest;
use App\Http\Requests\Api\CategoryUpdateRequest;
use App\Http\Requests\Api\IDListRequest;
use App\Models\Category;
use App\Traits\ResponseTrait;

class CategoryController extends Controller
{
    use ResponseTrait;

    public function index(CategoryFilterRequest $request)
    {
        $result = Category::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "Category List");
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $category->id], "Category Created");
    }

    public function show(Category $category)
    {
        $category->load(["feature_image", "parent"]);
        return $this->responseSuccess($category, "Success");
    }

    public function featureUpdate(IDListRequest $request)
    {
        try {
            Category::query()->update([
                "feature_no" => 0
            ]);
            foreach ($request->ids as $k => $id) {
                Category::where("id", $id)
                    ->update([
                        "feature_no" => $k + 1
                    ]);
            }
            return $this->responseSuccess([], "Success");
        } catch (\Exception $ex) {
            return $this->responseError($ex->getMessage());
        }
    }

    public function trendingUpdate(IDListRequest $request)
    {
        try {
            Category::query()->update([
                "on_trending" => 0
            ]);
            Category::whereIn("id", $request->ids)
                ->update([
                    "on_trending" => 1
                ]);
            return $this->responseSuccess([], "Success");
        } catch (\Exception $ex) {
            return $this->responseError($ex->getMessage());
        }
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->safe()->toArray());
        return $this->responseSuccess(["id" => $category->id], "Category Updated");
    }

    public function catlist()
    {
        $data = Category::getCatList();
        return $this->responseSuccess($data, "Category List");
    }

    public function getLeaf($parent)
    {
        $query = Category::query();
        $category = $query->where(["isActive" => 1])
            ->withCount(["childs"])
            ->get();
        return $this->responseSuccess($category, "Leaf List");
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->responseSuccess([], "Category Deleted");
    }
}
