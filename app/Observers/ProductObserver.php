<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    public function updating(Product $product): void
    {
        Storage::disk('images')->delete($product->getOriginal('name'));
    }

    public function deleting(Product $product): void
    {
        foreach ($product->images as $image) {
            Storage::disk('images')->delete($image->name);
        }
    }
}
