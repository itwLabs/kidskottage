<?php

namespace App\Models;

use App\Enums\SectionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use App\Traits\FilterableTrait;
use App\Traits\ResourceTrait;
use Illuminate\Database\Eloquent\Builder;

class Section extends Model
{
    use HasFactory, ResourceTrait, FilterableTrait;

    public function scopeTypeAds(Builder $query): Builder
    {
        return $query->where("type", SectionType::ads->value);
    }

    public function scopeTypeLetter(Builder $query): Builder
    {
        return $query->where("type", SectionType::newsletter->value);
    }

    public function scopeTypeService(Builder $query): Builder
    {
        return $query->where("type", SectionType::service->value);
    }

    public function scopeTypeGender(Builder $query): Builder
    {
        return $query->where("type", SectionType::gender->value);
    }
}
