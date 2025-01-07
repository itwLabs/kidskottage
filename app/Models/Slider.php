<?php

namespace App\Models;

use App\Traits\ResourceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\FilterableTrait;
use App\Traits\SeoTrait;

class Slider extends Model
{
    use HasFactory, ResourceTrait, FilterableTrait, SeoTrait;
    
}
