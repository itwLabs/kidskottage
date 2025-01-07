<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Traits\ResponseTrait;

class PromotionController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $result = Promotion::with(["products"])->get();
        return $this->responseSuccess($result, "Promotion List");
    }

    public function show(Promotion $promotion)
    {
        $promotion->load(["feature_image", "cover_image", "products"]);
        return $this->responseSuccess($promotion, "Success");
    }
}
