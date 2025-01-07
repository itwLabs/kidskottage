<?php

namespace App\Jobs;

use App\Mail\OrderPaymentMail;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\SalePayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OrderPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $paymentId;
    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payment = SalePayment::where(["id" => $this->paymentId])->first();
        Mail::to($payment->order->user->email)->send(new OrderPaymentMail($payment->order->user, $payment));
    }
}
