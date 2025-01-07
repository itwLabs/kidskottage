<?php

namespace App\Models;

use App\Traits\ResourceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\FilterableTrait;
use App\Traits\SeoTrait;

class Promotion extends Model
{
    use HasFactory, ResourceTrait, FilterableTrait, SeoTrait;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
