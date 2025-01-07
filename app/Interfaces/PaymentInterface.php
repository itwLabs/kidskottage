<?php

namespace App\Interfaces;

use App\Models\Order;
use App\Models\PaymentLog;
use App\Models\Product;
use Faker\Core\Number;
use Illuminate\Http\Request;

interface PaymentInterface
{
    public function init(int $transactionId, $amount): void;
    public function initPayment(Request $request): void;
    public function getTransactionID(): string;
    public function getPaymentForm();
    public function getAmount(): float;
    public function getMethodID(): string;
    public function getSuccessUrl(): string;
}
