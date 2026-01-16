<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dispatcher_id',
        'delivery_id',
        'invoice',
        'total',
        'phone',
        'address',
        'status',
        'payment_id',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'total' => 'int',
    ];

    /**
     * Get the user that owns the Order
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function dispatcher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispatcher_id', 'id');
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_id', 'id');
    }
}
