<?php

namespace App\Services;

use App\Interfaces\PaymentInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EsewaPayment implements PaymentInterface
{

    private $_successUrl, $_failUrl, $_transactionID, $_amount;
    function __construct()
    {
        $this->_amount = 0;
        $this->_successUrl = route("customer.payment.esewa");
        $this->_failUrl = route("customer.payment.failed");
    }
    public function initPayment(Request $request): void
    {
        try {
            $decode = base64_decode($request->data);
            $response = json_decode($decode, true);
            $this->_transactionID = $response["transaction_uuid"];
            $this->_amount = (float)str_replace(",", "", $response["total_amount"]);
        } catch (\Exception $ex) {
            throw new BadRequestException("Unable to handle your request");
        }
    }

    public function init($transactionId, $amount): void
    {
        $this->_transactionID = $transactionId;
        $this->_amount = $amount;
    }

    public function getPaymentForm()
    {
        return view("payment._esewa", ["esewa" => $this])->render();
    }


    public function getRequestData(): string
    {
        return json_encode([
            "signature" => $this->getSignature(),
            "transaction_uuid" => $this->getTransactionID(),
            "signed_field_names" => $this->getSignedField(),
            "total_amount" => $this->getAmount()
        ]);
    }

    public function getAmount(): float
    {
        return (float)$this->_amount;
    }

    public function getFormID(): string
    {
        return "esewaForm";
    }

    public function getMethodID(): string
    {
        return "Esewa";
    }

    public function getTransactionID(): string
    {
        return $this->_transactionID;
    }

    public function getMerID()
    {
        return env("ESEWA_MERCHANT_ID", "EPAYTEST");
    }
    public function getSuccessUrl(): string
    {
        return $this->_successUrl;
    }

    public function getFailUrl(): string
    {
        return $this->_failUrl;
    }

    public function getSecode()
    {
        return env("ESEWA_MERCHANT_KEY", "8gBm/:&EnhH.1/q");
    }

    public function getSignature()
    {
        return base64_encode(hash_hmac('sha256', "total_amount=" . $this->getAmount() . ",transaction_uuid=" . $this->_transactionID . ",product_code=" . $this->getMerID(), $this->getSecode(), true));
    }

    public function getSignedField()
    {
        return "total_amount,transaction_uuid,product_code";
    }

    public function getSubmitUrl()
    {
        return env("ESEWA_URL", "https://rc-epay.esewa.com.np/api/epay/main/v2/form");
    }
}


// eSewa ID: 9806800001/2/3/4/5
// Password: Nepal@123
// MPIN: 1122 (for application only)
// Merchant ID/Service Code: EPAYTEST
// Token:123456