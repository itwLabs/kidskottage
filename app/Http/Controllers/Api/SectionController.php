<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SectionFilterRequest;
use App\Http\Requests\Api\SectionRequest;
use App\Http\Requests\Api\SectionUpdateRequest;
use App\Models\Section;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    use ResponseTrait;

    public function index(SectionFilterRequest $request)
    {
        $result = Section::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "Section List");
    }

    public function store(SectionRequest $request)
    {
        $section = Section::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $section->id], "Section Created");
    }

    public function show(Section $section)
    {
        $section->load(["feature_image"]);
        return $this->responseSuccess($section, "Success");
    }

    public function update(SectionUpdateRequest $request, Section $section)
    {
        $section->update($request->safe()->toArray());
        return $this->responseSuccess(["id" => $section->id], "Section Updated");
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return $this->responseSuccess([], "Section Deleted");
    }
}
