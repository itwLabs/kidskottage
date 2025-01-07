<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\BrandUpdateRequest;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BrandRequest;
use App\Http\Requests\Api\SubscriberFilterRequest;
use App\Models\Subscriber;
use App\Traits\ResponseTrait;

class SubscriberController extends Controller
{
    use ResponseTrait;

    public function index(SubscriberFilterRequest $request)
    {
        $result = Subscriber::filter($request)->get();
        return $this->responseSuccess($result, "Subscriber List");
    }
}
