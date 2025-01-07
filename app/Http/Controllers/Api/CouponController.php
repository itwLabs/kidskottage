<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CouponRequest;
use App\Http\Requests\Api\CouponUpdateRequest;
use App\Http\Requests\Api\CouponFilterRequest;
use App\Models\Coupon;
use App\Traits\ResponseTrait;

class CouponController extends Controller
{
    use ResponseTrait;

    public function index(CouponFilterRequest $request)
    {
        $result = Coupon::filter($request)->get();
        return $this->responseSuccess($result, "Coupon List");
    }


    public function store(CouponRequest $request)
    {
        $coupon = Coupon::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $coupon->id], "Coupon Created");
    }

    public function show(Coupon $coupon)
    {
        return $this->responseSuccess($coupon, "Success");
    }

    public function update(CouponUpdateRequest $request, Coupon $coupon)
    {
        $coupon->update($request->safe()->toArray());
        return $this->responseSuccess(["id" => $coupon->id], "Coupon Updated");
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return $this->responseSuccess([], "Coupon Deleted");
    }
}
