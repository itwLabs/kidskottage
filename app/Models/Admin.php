<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\FilterableTrait;
use App\Traits\ResourceTrait;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Model
{
    use HasApiTokens, ResourceTrait, HasFactory, Notifiable, FilterableTrait, ResourceTrait;

    protected $hidden = ["password"];
}
