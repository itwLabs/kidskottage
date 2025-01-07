<?php

namespace App\Services;

use App\Interfaces\PaymentInterface;
use App\Models\Order;
use App\Models\PaymentLog;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PaymentService
{
    private $_payment;

    function makePayment(Request $request, PaymentInterface $payment)
    {
        $log = new PaymentLog();
        try {
            $this->_payment = $payment;
            $this->_payment->initPayment($request);
            $log = PaymentLog::where(["transaction_id" => $this->_payment->getTransactionID(), "status" => 0])
                ->firstOrFail();
            if ($this->_payment->getAmount() !== (float)$log->amount) {
                throw new BadRequestException("Payment amount does not match. Order amount : {$log->amount}, paid amount : " . $this->_payment->getAmount());
            }
            $saleModal = $log->sale;
            if ($saleModal->payment > 0) {
                throw new BadRequestException("Payment already made for order : " . $saleModal->code);
            }
            $saleModal->payment = $this->_payment->getAmount();
            $saleModal->payment_method = $this->_payment->getMethodID();
            $saleModal->save();
            $log->payment_method = $this->_payment->getMethodID();
            $log->response = $request->data;
            $log->message = "Payment Success";
            $log->status = 1;
            $log->save();
            return $saleModal;
        } catch (\Exception $ex) {
            $log->response = $request->data;
            $log->message = $ex->getMessage();
            $log->status = 3;
            $log->save();
            throw new BadRequestException("Unable to handle your request");
        }
    }
    function paymentInit(Sale $sale)
    {
        $trid = Str::random(20);
        PaymentLog::create([
            "transaction_id" => $trid,
            "order_id" => $sale->id,
            "amount" => $sale->total_amount
        ]);
        return $trid;
    }

    public function getSuccessUrl(): string
    {
        return $this->_payment->getSuccessUrl();
    }
}
