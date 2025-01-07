<?php

namespace App\Jobs;

use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OrderPlacedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $saleId;
    public function __construct($saleId)
    {
        $this->saleId = $saleId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Sale::where(["id" => $this->saleId])->first();
        Mail::to($order->user->email)->send(new OrderPlacedMail($order->user, $order));
    }
}
