<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use App\Traits\ResourceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\SeoTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $table = "category";
    use HasFactory, ResourceTrait, FilterableTrait, SeoTrait;
    protected $resource = ["feature_image", "cover_image"];

    public function products()
    {
        return $this->belongsToMany(Product::class, "product_category");
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function childs(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public static function getCatList()
    {
        $query = "
        WITH RECURSIVE CategoryHierarchy AS (
            SELECT id, name, parent_id, isActive, CONCAT(name) as path
            FROM category
            WHERE parent_id IS NULL AND isActive = 1
            UNION ALL
            SELECT c.id, c.name, c.parent_id, c.isActive, CONCAT(ch.path, ' / ', c.name) as path
            FROM category c
            INNER JOIN CategoryHierarchy ch ON c.parent_id = ch.id
            WHERE c.isActive = 1
        )
        SELECT * FROM CategoryHierarchy
        WHERE id NOT IN (SELECT DISTINCT parent_id FROM category WHERE parent_id IS NOT NULL)
        ";
        return DB::select($query);
    }
}
