<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Contactus extends Model
{
    protected $table = "contactus";
    use HasFactory, FilterableTrait;
}
