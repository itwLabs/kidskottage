<?php

namespace App\Http\Controllers\App;

use App\Enums\PageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PageFilterRequest;
use App\Http\Requests\Api\PageRequest;
use App\Http\Requests\Api\PageUpdateRequest;
use App\Models\Page;
use App\Traits\ResponseTrait;

class PageController extends Controller
{
    use ResponseTrait;

    public function show(Page $page)
    {
        $page->load(["feature_image"]);
        return $this->responseSuccess($page, "Success");
    }

    public function type($type)
    {
        $page = Page::where(["type" => $type])->with(["feature_image"])->firstOrFail();
        return $this->responseSuccess($page, "Success");
    }

    public function typeList()
    {
        $types = array_column(PageType::cases(), "value");
        $typeList = [];
        foreach ($types as $k => $t) {
            $typeList[$k] = $t;
        }
        return $this->responseSuccess($typeList, "Page Type List");
    }
}
