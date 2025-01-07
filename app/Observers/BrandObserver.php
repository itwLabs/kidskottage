<?php

namespace App\Observers;

use App\Models\Brand;

class BrandObserver
{
    public function creating(Brand $brand): void
    {
        if (is_null($brand->order)) {
            $brand->order = Brand::max('order') + 1;
            return;
        }
    }

    public function deleted(Brand $brand): void
    {
        $lowerPriorityBrands = Brand::select(['id', 'order'])->where('order', '>', $brand->order)->get();

        foreach ($lowerPriorityBrands as $lowerPriorityBrand) {
            $lowerPriorityBrand->order--;
            $lowerPriorityBrand->saveQuietly();
        }
    }
}
