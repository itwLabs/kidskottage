<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\ResourceTrait;
use App\Traits\SeoTrait;

class Setting extends Model
{
    use HasFactory, ResourceTrait, SeoTrait;
    public $resource = ["logo"];
}
