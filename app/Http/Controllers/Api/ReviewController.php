<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FaqFilterRequest;
use App\Http\Requests\Api\FaqRequest;
use App\Http\Requests\Api\FaqUpdateRequest;
use App\Http\Requests\Api\ReviewFilterRequest;
use App\Http\Requests\Api\ReviewUpdateRequest;
use App\Models\Faq;
use App\Models\Review;
use App\Traits\ResponseTrait;

class ReviewController extends Controller
{
    use ResponseTrait;

    public function index(ReviewFilterRequest $request)
    {
        $result = Review::filter($request)->with(["user", "product"])->get();
        return $this->responseSuccess($result, "Review List");
    }

    public function show(Review $review)
    {
        $review->load(["user", "product"]);
        return $this->responseSuccess($review, "Review Detail");
    }

    public function update(ReviewUpdateRequest $request, Review $review)
    {
        $review->update($request->safe()->toArray());
        return $this->responseSuccess(["id" => $review->id], "Review Updated");
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return $this->responseSuccess([], "Review Deleted");
    }
}
