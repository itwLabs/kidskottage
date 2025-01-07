<?php

namespace App\Http\Controllers\Api;

use App\Enums\SaleItemStatus;
use App\Enums\SaleState;
use App\Enums\SaleStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentRequest;
use App\Http\Requests\Api\SaleFilterRequest;
use App\Http\Requests\Api\SaleUpdateRequest;
use App\Http\Requests\Api\SaleItemUpdateRequest;
use App\Jobs\OrderCanceledJob;
use App\Jobs\OrderDeliveredJob;
use App\Jobs\OrderPaymentJob;
use App\Jobs\OrderUpdateJob;
use App\Jobs\SalePaymentJob;
use App\Models\SalePayment;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Sale;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{

    use ResponseTrait;

    public function index(SaleFilterRequest $request)
    {
        $result = Sale::filter($request)->with(['items.product', 'user'])->get();
        return $this->responseSuccess($result, "Sale List");
    }

    public function show(Sale $sale)
    {
        $sale->load(["items.product", "user", "addresses", "payments"]);
        return $this->responseSuccess($sale, "Sale Detail");
    }

    public function update(Sale $sale, SaleUpdateRequest $update)
    {
        $sale->update($update->safe()->all());
        $sale->refresh();
        return $this->responseSuccess($sale, "Sale Detail");
    }

    public function changeStatus(Sale $sale, SaleItemUpdateRequest $update)
    {
        DB::beginTransaction();
        try {
            $data = $update->safe()->all();
            $status = $data["status"];
            $item = SaleItem::with(["product"])->findOrFail($data["id"]);
            $product = $item->product;
            $oldStatus = $item->status;
            $item->update(["status" => $status]);
            Sale::updateStatus($sale);
            if ($status == SaleItemStatus::delivered->value) {
                Product::where('id', $product->id)->decrement('stock', $item->qty);
            }
            if ($oldStatus == SaleItemStatus::delivered->value && $status != SaleItemStatus::delivered->value) {
                Product::where('id', $product->id)->increment('stock', $item->qty);
            }

            $newSale = $sale->refresh();
            if ($newSale->status == SaleStatus::cancelled->value) {
                // dispatch(new OrderCanceledJob($newSale->id));
            } elseif ($newSale->state == "delivered") {
                // dispatch(new OrderDeliveredJob($newSale->id));
            } else {
                // dispatch(new OrderUpdateJob($newSale->id));
            }
            DB::commit();
            return $this->responseSuccess([], "Status Updated");
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->responseError([], $ex->getMessage());
        }
        return $this->responseError([], 'Status Update Failed');
    }
    public function getPayment(Sale $sale)
    {
        $payments = $sale->payments;
        return $this->responseSuccess($payments, "Payment List");
    }
    public function addPayment(Sale $sale, PaymentRequest $paymentRequest)
    {
        try {
            $paymentModel = new SalePayment($paymentRequest->validated());
            $sale->payments()->save($paymentModel);
            $amount = $sale->payments()->sum("amount");
            $sale->update(["payment" => $amount]);
            dispatch(new OrderPaymentJob($paymentModel->id));
            return $this->responseSuccess([], "Payment Added Successfully!");
        } catch (\Exception $ex) {
            return $this->responseError([], $ex->getMessage());
        }
        return $this->responseError([], 'Payment Add Failed');
    }
    public function deletePayment(SalePayment $payment)
    {
        DB::beginTransaction();
        try {
            $sale = $payment->sale;
            $payment->delete();
            $amount = $sale->payments()->sum("amount");
            $sale->update(["paid_amount" => $amount]);
            DB::commit();
            return $this->responseSuccess([], "Payment Deleted Successfully!");
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->responseError(["error" => $ex->getMessage()]);
        }
    }
}
