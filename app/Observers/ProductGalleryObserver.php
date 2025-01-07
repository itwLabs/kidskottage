<?php

namespace App\Observers;

use App\Models\ProductGallery;

class ProductGalleryObserver
{
    public function creating(ProductGallery $product_gallery): void
    {
        if (is_null($product_gallery->order)) {
            $product_gallery->order = ProductGallery::max('order') + 1;
            return;
        }
    }

    public function deleted(ProductGallery $product_gallery): void
    {
        $lowerPriorityProductGalleries = ProductGallery::select(['id', 'order'])->where('order', '>', $product_gallery->order)->get();

        foreach ($lowerPriorityProductGalleries as $lowerPriorityProductGallery) {
            $lowerPriorityProductGallery->order--;
            $lowerPriorityProductGallery->saveQuietly();
        }
    }
}
