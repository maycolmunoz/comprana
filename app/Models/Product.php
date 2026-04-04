<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(ProductObserver::class)]
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'images',
        'price',
        'stock',
        'section_id',
    ];

    protected $casts = [
        'price' => 'int',
        'images' => 'array',
    ];

    /**
     * Get the section that owns the Product
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function scopeSearch($query, string $search = '')
    {
        $query->where('name', 'LIKE', "%{$search}%");
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }
}
