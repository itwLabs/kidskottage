<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\FilterableTrait;

class Coupon extends Model
{
    use HasFactory, FilterableTrait;
}
