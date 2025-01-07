<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ProductUpdateRequest;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AttributeRequest;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Requests\Api\ProductFilterRequest;
use App\Http\Requests\Api\ProductGalleryRequest;
use App\Models\Resource;
use App\Repositories\Api\AttributeRepository;
use App\Traits\ResponseTrait;

class AttributeController extends Controller
{
    use ResponseTrait;

    public function __construct(private AttributeRepository $attrRepo)
    {
    }

    public function index($product)
    {
        $result = $this->attrRepo->getAll($product);
        return $this->responseSuccess($result, "Attribute List");
    }

    public function store(AttributeRequest $request, Product $product)
    {
        $id = $this->attrRepo->create($product, $request->safe()->toArray());
        return $this->responseSuccess($id, "Attribute Created");
    }
}
