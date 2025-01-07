<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function creating(Product $product)
    {
        $product->code = 'KK-' . random_int(1000, 9999) . '-' . random_int(100000, 999999);
    }
}
