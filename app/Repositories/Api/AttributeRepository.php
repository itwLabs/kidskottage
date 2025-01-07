<?php

namespace App\Repositories\Api;

use App\Abstracts\FormRequest;
use App\Interfaces\CrudInterface;
use App\Interfaces\DBPrepareableInterface;
use App\Models\Product;
use App\Models\ProductAttribute;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AttributeRepository implements DBPrepareableInterface
{
    public function getAll($id)
    {
        $query = ProductAttribute::where(["product_id" => $id])->get();
        return $query->map(function ($d) {
            $d["attr"] = json_decode($d["attr"]);
            return $d;
        });
    }

    public function getById(int $id): ?Product
    {
        $attribute = ProductAttribute::find($id);
        if (empty($attribute)) {
            return throw new Exception("Product does not exist.", Response::HTTP_NOT_FOUND);
        }
        return $attribute;
    }

    public function create(Product $product, array $data)
    {
        DB::beginTransaction();
        try {
            $data = $this->prepareForBD($data, $product);
            $this->delete($product->id);
            $result = [];
            foreach ($data as $d) {
                $result[] = ProductAttribute::create($d);
            }
            DB::commit();
            return $result;
        } catch (\Exception $ex) {
            DB::rollBack();
            return throw new Exception("Error while creating attribute", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function delete(int $id)
    {
        ProductAttribute::where(["product_id" => $id])->delete();
    }

    public function prepareForBD(array $data, ?Product $product = null): array
    {
        $result = [];
        $default = 0;
        foreach ($data["attr"] as $k => $d) {
            $result[$k] = [
                "product_id" => $product->id,
                "attr" => json_encode($d["attr"]),
                "price" => $d["price"],
                "stock" => $d["stock"],
                "is_default" => $d["is_default"] ? 1 : 0
            ];
            if ($result[$k]["is_default"] == 1) {
                $default++;
            }
        }
        if ($default != 1) {
            return throw new Exception("There must be exactly one default attribute", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $result;
    }
}
