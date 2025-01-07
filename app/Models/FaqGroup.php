<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class FaqGroup extends Model
{
    use HasFactory, FilterableTrait;
}
