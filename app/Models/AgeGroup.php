<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use App\Traits\ResourceTrait;
use App\Traits\SeoTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class AgeGroup extends Model
{
    use HasFactory, ResourceTrait, FilterableTrait, SeoTrait;
    protected $resource = ["feature_image"];
    protected $seo_description = "short_name";
}
