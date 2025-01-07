<?php

namespace App\Repositories\Api;

use App\Abstracts\FormRequest;
use App\Enums\ProductAttribute;
use App\Interfaces\CrudInterface;
use App\Interfaces\DBPrepareableInterface;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductRepository implements CrudInterface, DBPrepareableInterface
{
    public function getAll(FormRequest $filter): Paginator
    {
        $query = Product::filter($filter)->with(["feature_image", "brand"]);
        return $query->paginate($filter['perPage']);
    }



    public function getFilterData(array $filterData): array
    {
        $defaultArgs = [
            'perPage' => 10,
            'search' => '',
            'orderBy' => 'id',
            'order' => 'desc'
        ];

        return array_merge($defaultArgs, $filterData);
    }

    public function getById(int $id): ?Product
    {
        $product = Product::with(["feature_image", "brand", "agegroup", "category"])->find($id);
        if (empty($product)) {
            return throw new Exception("Product does not exist.", Response::HTTP_NOT_FOUND);
        }
        return $product;
    }

    public function getAttr()
    {
        $result = [];
        $attr = array_column(ProductAttribute::cases(), "value");
        foreach ($attr as $a) {
            $result[] = ["value" => $a];
        }
        return $result;
    }

    public function create(array $data): ?Product
    {
        DB::beginTransaction();
        // try {
            [$agegroup, $category, $data] = $this->prepareForBD($data);
            $product = Product::create($data);
            $product->category()->attach($category);
            $product->agegroup()->attach($agegroup);
            DB::commit();
            return $product;
        // } catch (\Exception $ex) {
        //     DB::rollBack();
        //     return throw new Exception("Error while creating product", Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }

    public function update(int $id, array $data): ?Product
    {
        DB::beginTransaction();
        try {
            $product = $this->getById($id);
            [$agegroup, $category, $data] = $this->prepareForBD($data);
            $updated = $product->update($data);
            if ($updated) {
                if ($category) {
                    $product->category()->sync($category);
                }
                if ($agegroup) {
                    $product->agegroup()->sync($agegroup);
                }
            }
            DB::commit();
            return $product;
        } catch (\Exception $ex) {
            DB::rollBack();
            return throw new Exception("Error while updating product", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(int $id): ?Product
    {
        $product = $this->getById($id);
        $deleted = $product->delete($product);
        if (!$deleted) {
            throw new Exception("Product could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $product;
    }

    public function prepareForBD(array $data, ?Product $product = null): array
    {

        $agegroup = null;
        $category = null;
        if (isset($data["agegroup"])) {
            $agegroup = $data["agegroup"];
            unset($data["agegroup"]);
        }
        if (isset($data["category"])) {
            $category = $data["category"];
            unset($data["category"]);
        }

        if (isset($data["attr"])) {
            $data["attr"] = implode(",", $data["attr"]);
        }

        return [$agegroup, $category, $data];
    }
}
