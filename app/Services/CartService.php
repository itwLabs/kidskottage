<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Sale;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartService
{

    private function getCart()
    {
        $_user = Auth::guard()->user();
        $cart = Cart::where(["user_id" => $_user->id])->first();
        if (!$cart) {
            $cart = Cart::create(["user_id" => $_user->id]);
        }
        return $cart;
    }

    public function get()
    {
        $_user = Auth::guard()->user();
        $this->updateCart();
        return Cart::where(["user_id" => $_user->id])->with(["items"])->first();
    }

    public function add(Product $product, ?ProductAttribute $attr = null, int $qty = 1)
    {
        [$attr, $data] = $this->processProduct($product, $qty, $attr?->id);
        $cardItem = CartItem::where([
            'cart_id' => $this->getCart()->id,
            'product_id' => $product->id,
        ])->when($attr != null, function ($query) use ($attr) {
            $query->where(["attr_id" => $attr->id]);
        })->first();

        if ($cardItem) {
            $cardItem->update(['qty' => $qty == null ? 1 : $qty]);
        } else {
            CartItem::create($data);
        }
        $this->updateCart();
        return true;
    }

    public function remove(CartItem $item)
    {
        if ($item->cart_id == $this->getCart()->id) {
            $item->delete();
            $this->updateCart();
            return true;
        }
        return false;
    }

    public function removeAll()
    {
        CartItem::where(["cart_id" => $this->getCart()->id])->delete();
        $this->updateCart();
        return true;
    }

    function applyCoupon(string $code)
    {
        $coupon = $this->validateCoupon($code);
        if (!$coupon) {
            throw new NotFoundException("Coupon not available");
        }
        $cartModal = $this->getCart();
        $cartModal->update([
            "coupon_code" => $coupon["code"],
            "coupon_amount" => $coupon["value"]
        ]);
        $this->updateCart();
        return true;
    }

    public function clearCoupon()
    {
        $cart = $this->getCart();
        $cart->update([
            "coupon_code" => "",
            "coupon_amount" => 0
        ]);
        $this->updateCart();
        return true;
    }

    public function checkOut($addressIds)
    {
        $_user = Auth::guard()->user();
        $this->updateCart();
        $addressList = UserAddress::where(["user_id" => $_user->id])->whereIn("id", $addressIds)->get();
        // if (count($addressList) == count($addressIds)) {
        //     throw new NotFoundException("User address does not exists");
        // }
        $cartModal = $this->get();
        if ($cartModal->item_count == 0) {
            throw new BadRequestException("Cart is empty");
        }
        if ($cartModal->coupon_code && !$this->validateCoupon($cartModal->coupon_code)) {
            throw new BadRequestException("Invalid coupon code");
        }
        DB::beginTransaction();
        try {
            $cartItems = $cartModal->items;
            $subtotal = 0;
            $saleItems = $cartItems->map(function ($item) use (&$subtotal) {
                $subtotal += $item->qty * $item->price;
                if ($item->attr_id) {
                    return [
                        "qty" => $item->qty,
                        "rate" => $item->price,
                        "product_id" => $item->product_id,
                        "attr_id" => $item->attr_id,
                        "attr" => $item->attr,
                    ];
                }
                return [
                    "qty" => $item->qty,
                    "rate" => $item->price,
                    "product_id" => $item->product_id,
                ];
            });
            $saleAddress = $addressList->map(function ($item) {
                return [
                    "name" => $item->name,
                    "email" => $item->email,
                    "phone" => $item->phone,
                    "type" => $item->type,
                    "address" => $item->address
                ];
            });

            $sales = Sale::create([
                "sub_amount" => $subtotal,
                "total_amount" => $cartModal->total_amount,
                "shipping_amount" => $cartModal->shipping_amount,
                "discount_code" => $cartModal->discount_code,
                "user_id" => $cartModal->user_id,
            ]);
            $sales->addresses()->createMany($saleAddress);
            $sales->items()->createMany($saleItems);
            $this->resetCart();
            DB::commit();
            return $sales;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    private function updateCart()
    {
        $cartModal = $this->getCart();
        $item = CartItem::where(["cart_id" => $cartModal->id])->get();
        $cart = [
            "total_amount" => 0,
            "item_count" => 0,
            "total_discount" => 0,
            "shipping_amount" => 0
        ];
        foreach ($item as $it) {
            $cart["total_amount"] += $it["price"] * $it["qty"];
            $cart["total_discount"] += $it["discount"];
            $cart["item_count"] += $it["qty"];
        }
        $cart["total_discount"] = $cart["total_discount"] + $cartModal->coupon_amount;
        $cart["total_amount"] +=  $cart["shipping_amount"] - $cart["total_discount"];
        if ($cart["total_amount"] < 0) {
            $cart["total_amount"] = 0;
        }
        $cartModal->update($cart);
    }


    private function processProduct(Product $product, int $qty, int $attrId = null)
    {
        $cartModal = $this->getCart();
        if ($product->qty > -1 && $product->qty < $qty) {
            throw new HttpException(400, "Stock is not available");
        }
        $data = [
            'cart_id' => $cartModal->id,
            'qty' => $qty,
            'product_id' => $product->id,
            'price' => $product->price,
            'discount' => ($product->price * ($product->discount / 100)) * $qty,
        ];
        $product_attr = null;
        if (!$attrId) {
            $product_attr = ProductAttribute::select(['id', 'price', 'attr'])->where(['product_id' => $product->id, 'id' => $attrId])->first();
        } else {
            $product_attr = ProductAttribute::select(['id', 'price', 'attr'])->where(['product_id' => $product->id, 'is_default' => "1"])->first();
        }
        if (!empty($product_attr)) {
            $data["attr_id"] = $product_attr->id;
            $data["attr"] = $product_attr->attr;
            $data['price'] = $product_attr->price + $product->price;
        }
        return [$product_attr, $data];
    }

    private function validateCoupon($code)
    {
        return Coupon::where('code', $code)
            ->where('uses_limit', '>', 0)
            ->where('ends_on', '>', date("Y-m-d"))
            ->first();
    }

    private function resetCart()
    {
        $cartModal = $this->getCart();
        CartItem::where(["cart_id" => $cartModal->id])->delete();
        $this->updateCart();
    }
}
