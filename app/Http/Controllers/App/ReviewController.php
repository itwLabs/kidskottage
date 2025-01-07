<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReviewFilterRequest;
use App\Http\Requests\App\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Services\ReviewService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use ResponseTrait;

    function __construct(private ReviewService $_reviewService) {}

    function initialService()
    {
        $auth = Auth::user();
        $this->_reviewService = new ReviewService($auth);
    }

    public function index(ReviewFilterRequest $request)
    {
        $result = Review::filter($request)->with(["user", "product"])->get();
        return $this->responseSuccess($result, "Review List");
    }

    public function myreview()
    {
        $result = $this->_reviewService->getMyReviews();
        return $this->responseSuccess($result, "My Review List");
    }

    public function show(Review $review)
    {
        $review = $this->_reviewService->getReview($review->id)?->load("product");
        return $this->responseSuccess($review, "Review Detail");
    }

    public function store(Product $product, ReviewRequest $request)
    {
        $review = $this->_reviewService->storeReview($product, $request);
        return $this->responseSuccess($review, "Review Added");
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $this->_reviewService->updateReview($review->id, $request);
        return $this->responseSuccess(["id" => $review->id], "Review Updated");
    }

    public function destroy(Review $review)
    {
        $this->_reviewService->deleteReview($review->id);
        return $this->responseSuccess([], "Review Deleted");
    }
}
