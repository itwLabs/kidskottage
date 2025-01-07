<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\FilterableTrait;
use App\Traits\ResourceTrait;

class Listing extends Model
{
    use HasFactory, ResourceTrait, FilterableTrait;

    public function products()
    {
        return $this->belongsToMany(Brand::class);
    }
}
