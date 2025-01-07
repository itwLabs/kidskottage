<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerFilterRequest;
use App\Models\User;
use App\Traits\ResponseTrait;

class CustomerController extends Controller
{
    use ResponseTrait;

    public function index(CustomerFilterRequest $request)
    {
        $result = User::filter($request)->with(["feature_image", "addresses"])->get();
        return $this->responseSuccess($result, "Customer List");
    }

    public function show(User $customer)
    {
        $customer->load(["feature_image"]);
        return $this->responseSuccess($customer, "Success");
    }
}
