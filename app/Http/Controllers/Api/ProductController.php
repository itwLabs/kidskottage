<?php

namespace App\Http\Controllers\Api;

use App\Enums\ProductAttribute;
use App\Http\Requests\Api\ProductUpdateRequest;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Requests\Api\ProductFilterRequest;
use App\Http\Requests\Api\ProductGalleryRequest;
use App\Http\Requests\FileRequest;
use App\Models\Resource;
use App\Repositories\Api\ProductRepository;
use App\Traits\ResponseTrait;

class ProductController extends Controller
{
    use ResponseTrait;

    public $productRepo;
    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function index(ProductFilterRequest $request)
    {
        $result = $this->productRepo->getAll($request);
        return $this->responseSuccess($result, "Product List");
    }

    public function store(ProductRequest $request)
    {
        $id = $this->productRepo->create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $id], "Product Created");
    }

    public function show($product)
    {
        $product = $this->productRepo->getById($product);
        return $this->responseSuccess($product, "Success");
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $this->productRepo->update($product->id, $request->safe()->toArray());
        return $this->responseSuccess(["id" => $product], "Product Updated");
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $this->responseSuccess([], "Product Deleted");
    }

    public function attribute_list()
    {
        $result = $this->productRepo->getAttr();
        return $this->responseSuccess($result, "Attribute List");
    }

    public function product_attribute()
    {
        $result = $this->productRepo->getAttr();
        return $this->responseSuccess($result, "Attribute List");
    }

    public function gallery(Product $product)
    {
        $result = $product->gallery()->orderBy("order", "ASC")->get();
        return $this->responseSuccess($result, "Gallery List");
    }

    public function gallerystore(ProductGalleryRequest $request, Product $product)
    {
        $resource = Resource::where(["id" => $request->feature_image])->first();
        $resource->update([
            "resoable_type" => Product::class,
            "resoable_id" => $product->id,
            "reso_type" => "feature_image",
            "moved" => 1,
            "order" => 2,
        ]);
        return $this->responseSuccess(["id" => $resource->id], "Product Created");
    }
}
