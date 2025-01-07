<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = "user_address";
}
