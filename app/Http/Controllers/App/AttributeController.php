<?php

namespace App\Http\Controllers\App;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Repositories\Api\AttributeRepository;
use App\Traits\ResponseTrait;

class AttributeController extends Controller
{
    use ResponseTrait;

    public function __construct(private AttributeRepository $attrRepo) {}

    public function index(Product $product)
    {
        $result = $this->attrRepo->getAll($product->id);
        return $this->responseSuccess($result, "Attribute List");
    }
}
