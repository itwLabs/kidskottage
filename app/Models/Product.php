<?php

namespace App\Models;

use App\Traits\ResourceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\FilterableTrait;
use App\Traits\SeoTrait;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, ResourceTrait, FilterableTrait, SeoTrait;

    public $multiple_resource = ["feature_image"];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->code = self::generateUniqueCode();
        });
    }

    private static function generateUniqueCode()
    {
        // Generate a random string of 10 characters
        $uniqueCode = Str::random(7);
        // Ensure the code is unique
        while (self::where('code', $uniqueCode)->exists()) {
            $uniqueCode = Str::random(7);
        }
        return $uniqueCode;
    }
    public function category()
    {
        return $this->belongsToMany(Category::class, "product_category");
    }

    public function gallery()
    {
        return $this->hasMany(Resource::class, "resoable_id")->where(["resoable_type" => Product::class, "reso_type" => "feature_image"]);
    }

    public function agegroup()
    {
        return $this->belongsToMany(AgeGroup::class, "product_age_group");
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attr()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
