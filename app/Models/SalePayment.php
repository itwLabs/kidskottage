<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\FilterableTrait;

class SalePayment extends Model
{
    use HasFactory, FilterableTrait;

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
