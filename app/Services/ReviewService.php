<?php

namespace App\Services;

use App\Enums\SaleItemStatus;
use App\Http\Requests\App\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Exception\NotFoundException;

class ReviewService
{

    private function getUser()
    {
        return Auth::guard()->user();
    }

    public function createReview(Product $product)
    {
        $user = $this->getUser();
        $this->_canReview($product->id, $user->id);
        return $user->reviews()->where(["product_id" => $product->id])->first();
    }

    public function storeReview(Product $product, ReviewRequest $data)
    {
        $reviewData = $data->safe()->all();
        unset($reviewData["agree"]);
        $user = $this->getUser();
        $this->_canReview($product->id, $user->id);
        $review = $user->reviews()->where(["product_id" => $product->id])->first();
        if ($review != null) {
            return $review->update($reviewData);
        }
        return Review::create([
            "product_id" => $product->id,
            "user_id" => $user->id,
            "rating" => $reviewData["rating"],
            "message" => $reviewData["message"]
        ]);
    }

    public function getReview(Review $review)
    {
        $reviewModal = $this->_hasReview($review->id);
        return $reviewModal;
    }

    public function updateReview(Review $review, ReviewRequest $data): bool
    {
        $reviewModal = $this->_hasReview($review->id);
        return $reviewModal->update($data);
    }

    public function deleteReview(Review $review): ?bool
    {
        $this->_hasReview($review->id);
        return $review->delete();
    }

    public function getMyReviews(): Collection
    {
        return Review::with('product:id,title')->where('user_id', $this->getUser()->id)->get();
    }

    private function _hasReview(int $reviewId)
    {
        $reviewModal = Review::where(["user_id" => $this->getUser()->id, "id" => $reviewId])
            ->first();
        if ($reviewModal == null) {
            throw new NotFoundException("Review not found");
        }
        return $reviewModal;
    }
    private function _canReview(int $productId, int $user_id): void
    {
        $check =  SaleItem::leftJoin("sales", "sales.id", "=", "sale_items.sale_id")
            ->where("sales.user_id", "=", $user_id)
            ->where([
                "sale_items.product_id" => $productId,
                "sale_items.status" => SaleItemStatus::delivered->value
            ])
            ->exists();
        if (!$check) {
            throw new NotFoundException("Review not found");
        }
    }
}
