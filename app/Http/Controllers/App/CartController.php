<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\CartRequest;
use App\Http\Requests\App\SaleRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\UserAddress;
use App\Services\CartService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ResponseTrait;

    function __construct(private CartService $cartService)
    {
        $this->cartService = new CartService();
    }

    public function index()
    {
        return $this->responseSuccess($this->cartService->get(), "Cart List");
    }

    public function addToCart(CartRequest $request)
    {
        $data = $request->safe()->all();
        $product = Product::where(["id" => $data["product_id"]])->firstOrFail();
        $attribute = null;
        if (!empty($data["attr_id"])) {
            $attribute = ProductAttribute::where(["product_id" => $product->id, "id" => $data["attr_id"]])->firstOrFail();
        }
        $this->cartService->add($product, $attribute, $data["qty"] ?? 1);
        return $this->responseSuccess([], "Added To Cart");
    }

    public function removeCart(CartItem $item)
    {
        $this->cartService->remove($item);
        return $this->responseSuccess([], "Removed From Cart");
    }

    public function removeAll()
    {
        $this->cartService->removeAll();
        return $this->responseSuccess([], "Removed All From Cart");
    }

    public function clearCoupon()
    {
        $this->cartService->clearCoupon();
        return $this->responseSuccess([], "Coupon Removed From Cart");
    }

    public function applyCoupon($code)
    {
        $this->cartService->applyCoupon($code);
        return $this->responseSuccess([], "Coupon added");
    }

    public function checkOut(SaleRequest $request)
    {
        $addressIds = $request->safe()->all();
        try {
            $sale = $this->cartService->checkOut($addressIds["address_ids"]);
            return $this->responseSuccess($sale, "Checkout success");
        } catch (\Exception $ex) {
            return $this->responseError([], $ex->getMessage());
        }
    }
}
