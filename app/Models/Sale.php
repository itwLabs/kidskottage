<?php

namespace App\Models;

use App\Enums\SaleItemStatus;
use App\Enums\SaleStateEnum;
use App\Enums\SaleStatus;
use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use Illuminate\Support\Str;

class Sale extends Model
{
    use HasFactory, FilterableTrait;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->code = (string) Str::upper(Str::random(10));
        });
    }

    public function addresses()
    {
        return $this->hasMany(SaleAddress::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(SalePayment::class);
    }


    static function updateStatus($order)
    {
        $totalAmount = 0;
        $delivered = 0;
        $canceled = 0;
        $pending = 0;
        $packaging = 0;
        $moved = 0;
        $items = $order->items()->get()->toArray();
        foreach ($items as $it) {
            if ($it["status"] != SaleItemStatus::cancel->value) {
                $totalAmount += $it["rate"] * $it["qty"];
            }
            foreach (SaleItemStatus::cases() as $i => $case) {
                if ($it["status"] == $case->value) {
                    if ($case->value == SaleItemStatus::delivered->value) {
                        $delivered++;
                    } elseif ($case->value == SaleItemStatus::cancel->value) {
                        $canceled++;
                    } elseif ($case->value == SaleItemStatus::packaging->value) {
                        $packaging++;
                    } elseif ($case->value == SaleItemStatus::moved->value) {
                        $moved++;
                    } elseif ($case->value == SaleItemStatus::pending->value) {
                        $pending++;
                    }
                }
            }
        }
        $totalItems = count($items);
        $stats = $order->status;
        $state = $order->state;
        if ($canceled == $totalItems) {
            $stats = SaleStatus::cancelled->value;
        } elseif (($pending + $canceled) == $totalItems) {
            $state = "pending";
        } elseif (($delivered + $canceled) == $totalItems) {
            $state = "delivered";
        } elseif (($packaging > $moved)) {
            $state = "processing";
        } else {
            $state = "ready";
        }
        $order->update(["status" => $stats, 'state' => $state, "sub_amount" => $totalAmount, "total_amount" => $totalAmount + $order->shipping_amount - $order->discount_amount]);
    }
}
