<?php

namespace App\Services;

use App\Enums\SaleItemStatus;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

//use Your Model

/**
 * Class OrderRepository.
 */
class SaleService
{

    public function listOrder()
    {
        $_user = Auth::user();
        $orders = Sale::where(["user_id" => $_user->id])->with(["items"])->orderBy("id", "DESC")->get();
        return $orders;
    }

    public function viewOrder($order)
    {
        $_user = Auth::user();
        $orders = Sale::where(["user_id" => $_user->id, "id" => $order])->with(['addresses', 'items'])->first();
        return $orders;
    }

    public function paymentOrder($order)
    {
        $_user = Auth::user();
        $order = Sale::where(["user_id" => $_user->id, "id" => $order])->first();
        if (!$order) {
            return false;
        }

        if (($order->total_amount - $order->paid_amount) <= 0) {
            return false;
        }
        return $order;
    }

    public function cancelOrderItem($item)
    {
        $_user = Auth::user();
        $itemModel = SaleItem::leftJoin("sales", "sales.id", "=", "sale_items.sale_id")
            ->where(["sales.user_id" => $_user->id, "sale_items.id" => $item])
            ->first();
        if ($itemModel != null) {
            $orderModel = $itemModel->sale;
            SaleItem::where(["id" => $item])->update(["status" => SaleItemStatus::cancel->value]);
            Sale::updateStatus($orderModel);
            // dispatch(new OrderCanceledJob($orderModel->id));
            return true;
        }
        return false;
    }

    public function cancelOrder($orderid)
    {
        $_user = Auth::user();
        try {
            $order = Sale::where(['sales.id' => $orderid, "user_id" => $_user->id])
                ->first();

            if ($order) {
                SaleItem::where(["sale_id" => $orderid])->update(["status" => SaleItemStatus::cancel->value]);
                Sale::updateStatus($order);
                // dispatch(new OrderCanceledJob($orderModel->id));
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            Log::error($th);
            return false;
        }
    }
}
