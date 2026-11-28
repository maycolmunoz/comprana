<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'active',
        'user_id',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the user that owns the Cart
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The products that belong to the Cart
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_product', 'cart_id', 'product_id')->withPivot('cant');
    }

    public function total(): int
    {
        return (int) $this->products->sum(
            fn (Product $product): int => (int) $product->pivot->cant * (int) $product->price
        );
    }

    /**
     * @return array<int, array{id: string, title: string, quantity: int, unit_price: int}>
     */
    public function toOrderItems(): array
    {
        return $this->products->map(fn (Product $product): array => [
            'id' => (string) $product->id,
            'title' => $product->name,
            'quantity' => (int) $product->pivot->cant,
            'unit_price' => (int) $product->price,
        ])->values()->all();
    }
}
