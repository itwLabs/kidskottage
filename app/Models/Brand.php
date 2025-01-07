<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use App\Traits\ResourceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\SeoTrait;

class Brand extends Model
{
    use HasFactory, ResourceTrait, FilterableTrait, SeoTrait;
    protected $resource = ["feature_image", "cover_image"];
}
