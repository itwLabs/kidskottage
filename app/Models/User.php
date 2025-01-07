<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\FilterableTrait;
use App\Traits\ResourceTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, ResourceTrait, HasFactory, Notifiable, FilterableTrait, ResourceTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // protected static function booted(): void
    // {
    //     if (!request()->is("api/*")) {
    //         static::addGlobalScope('activeWeb', function (Builder $query) {
    //             $query->where('status', 1);
    //         });
    //     }
    // }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, "user_id");
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, "user_id");
    }
}
