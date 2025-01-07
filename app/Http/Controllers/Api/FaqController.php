<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FaqFilterRequest;
use App\Http\Requests\Api\FaqRequest;
use App\Http\Requests\Api\FaqUpdateRequest;
use App\Models\Faq;
use App\Traits\ResponseTrait;

class FaqController extends Controller
{
    use ResponseTrait;

    public function index(FaqFilterRequest $request)
    {
        $result = Faq::filter($request)->get();
        return $this->responseSuccess($result, "Faq List");
    }

    public function store(FaqRequest $request)
    {
        $faq = Faq::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $faq->id], "Faq Created");
    }

    public function show(Faq $faq)
    {
        return $this->responseSuccess($faq, "Success");
    }

    public function update(FaqUpdateRequest $request, Faq $faq)
    {
        $faq->update($request->safe()->toArray());
        return $this->responseSuccess(["id" => $faq->id], "Faq Updated");
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return $this->responseSuccess([], "Faq Deleted");
    }
}
