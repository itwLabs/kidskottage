<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'amount' => 'float',
    ];
    function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
