<?php

namespace App\Http\Controllers\Api;

use App\Enums\PageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PageFilterRequest;
use App\Http\Requests\Api\PageRequest;
use App\Http\Requests\Api\PageUpdateRequest;
use App\Models\Page;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    use ResponseTrait;

    public function index(PageFilterRequest $request)
    {
        $result = Page::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "Page List");
    }

    public function store(PageRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->safe()->toArray();
            if ($data["type"] != PageType::page->value && $data["isActive"] == 1) {
                Page::where(["type" => $data["type"]])->update(["isActive" => 0]);
            }
            $page = Page::create($data);
            DB::commit();
            return $this->responseSuccess(["id" => $page->id], "Page Created");
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->responseError(["message" => $ex->getMessage()], $ex->getMessage(), $ex->getCode());
        }
    }

    public function show(Page $page)
    {
        $page->load(["feature_image"]);
        return $this->responseSuccess($page, "Success");
    }

    public function update(PageUpdateRequest $request, Page $page)
    {
        DB::beginTransaction();
        try {
            $data = $request->safe()->toArray();
            if ($data["type"] != PageType::page->value && $data["isActive"] == 1) {
                Page::where(["type" => $data["type"]])->update(["isActive" => 0]);
            }
            $page->update($data);
            DB::commit();
            return $this->responseSuccess(["id" => $page->id], "Page Updated");
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->responseError(["message" => $ex->getMessage()], $ex->getMessage(), $ex->getCode());
        }
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return $this->responseSuccess([], "Page Deleted");
    }


    public function types()
    {
        $types = array_column(PageType::cases(), "value");
        $typeList = [];
        foreach ($types as $k => $t) {
            $typeList[$k] = [
                "id" => $t,
                "name" => $t,
            ];
        }
        return $this->responseSuccess($typeList, "Page Type List");
    }
}
