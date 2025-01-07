<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SectionFilterRequest;
use App\Models\Section;
use App\Traits\ResponseTrait;

class SectionController extends Controller
{
    use ResponseTrait;

    public function index(SectionFilterRequest $request)
    {
        $result = Section::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "Section List");
    }

    public function show(Section $section)
    {
        $section->load(["feature_image"]);
        return $this->responseSuccess($section, "Success");
    }
}
