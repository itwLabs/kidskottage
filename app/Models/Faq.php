<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use App\Traits\ResourceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Faq extends Model
{
    use HasFactory, ResourceTrait, FilterableTrait;
    protected $resource = ["feature_image", "cover_image"];
}
