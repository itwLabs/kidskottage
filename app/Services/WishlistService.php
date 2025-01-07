<?php

namespace App\Services;

use App\Http\Requests\App\WishlistRequest;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    private function getUserId()
    {
        return Auth::guard()->user()->id;
    }

    public function toggle(WishlistRequest $request)
    {
        $data = $request->safe()->all();
        $data["user_id"] = $this->getUserId();
        $delete = Wishlist::where(["user_id" => $data["user_id"], "product_id" => $data["product_id"]])->delete();
        if ($delete) {
            return "delete";
        }
        Wishlist::create($data);
        return "create";
    }

    public function getAll()
    {
        return Wishlist::with(['product.feature_image'])->where(["user_id" => $this->getUserId()])->get();
    }

    public function delete($id): ?bool
    {
        Wishlist::where(["user_id" => $this->getUserId(), "id" => $id])->delete();
        return true;
    }

    public function deleteAll(): ?bool
    {
        Wishlist::where(["user_id" => $this->getUserId()])->delete();
        return true;
    }
}
