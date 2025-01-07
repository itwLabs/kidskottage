<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\WishlistRequest;
use App\Models\Product;
use App\Models\WishList;
use App\Repositories\WishlistRepository;
use App\Services\WishlistService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    use ResponseTrait;
    private WishlistService $wishService;

    public function __construct()
    {
        $this->wishService = new WishlistService();
    }
    public function index()
    {
        return $this->responseSuccess($this->wishService->getAll(), "Wishlist");
    }

    public function toggle(WishlistRequest $request)
    {
        Product::where(["id" => $request->product_id])->firstOrFail();
        $flag = $this->wishService->toggle($request);
        if ($flag == "create") {
            return $this->responseSuccess(["flag" => $flag], "Product added to wishlist");
        }
        return $this->responseSuccess(["flag" => $flag], "Product removed from wishlist");
    }

    public function delete($id): JsonResponse
    {
        $this->wishService->delete($id);
        return $this->responseSuccess([], "Product removed from wishlist");
    }

    public function removeAll(): JsonResponse
    {
        $this->wishService->deleteAll();
        return $this->responseSuccess([], "All product removed from wishlist");
    }
}
