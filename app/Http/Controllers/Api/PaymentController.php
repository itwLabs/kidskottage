<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentFilterRequest;
use App\Http\Requests\Api\PaymentRequest;
use App\Jobs\OrderPaymentJob;
use App\Models\SalePayment;
use App\Models\Sale;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    use ResponseTrait;

    public function index(PaymentFilterRequest $request)
    {
        $result = SalePayment::filter($request)->get();
        return $this->responseSuccess($result, "Sale Payment List");
    }

    public function store(PaymentRequest $paymentRequest)
    {
        DB::beginTransaction();
        try {
            $data = $paymentRequest->safe()->all();
            $sale = Sale::where(["id" => $data["sale_id"]])->firstOrFail();
            $paymentModel = new SalePayment($paymentRequest->validated());
            $sale->payments()->save($paymentModel);
            $amount = $sale->payments()->sum("amount");
            $sale->update(["paid_amount" => $amount]);
            // dispatch(new OrderPaymentJob($paymentModel->id));
            DB::commit();
            return $this->responseSuccess([], "Payment Added Successfully!");
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->responseError([], $ex->getMessage());
        }
        return $this->responseError([], 'Payment Add Failed');
    }

    public function destroy(SalePayment $payment)
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
