<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use App\Traits\ResourceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\SeoTrait;

class Blog extends Model
{
    use HasFactory, ResourceTrait, FilterableTrait, SeoTrait;
    protected $resource = ["feature_image", "cover_image"];
    protected $seo_description = "short_name";
}
