<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();
        $traits = self::getAllTraits();
        foreach ($traits as $trait) {
            $name = "boot$trait";
            if (method_exists($trait, $name)) {
                self::$name();
            }
        }
    }

    public function scopeActive(Builder $q)
    {
        $q->where(["isActive" => Status::true->value]);
    }


    public function scopeSorted(Builder $q)
    {
        $q->orderBy("order", "ASC");
    }

    private static function getAllTraits()
    {
        $traits = [];
        $class = get_called_class();
        do {
            $traits = array_merge(class_uses($class), $traits);
            $class = get_parent_class($class);
        } while ($class);
        return array_unique($traits);
    }
}
