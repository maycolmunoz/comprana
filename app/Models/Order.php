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
        'customer_id',
        'dispatcher_id',
        'delivery_id',
        'invoice',
        'total',
        'phone',
        'address',
        'status',
        'payment_id',
        'payment_status',
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

    /**
     * Alias of customer(), used by invoices and the admin panel.
     */
    public function user(): BelongsTo
    {
        return $this->customer();
    }

    public function paymentStatusLabel(): string
    {
        return match ($this->payment_status) {
            'approved' => 'Pagado',
            'pending', 'in_process' => 'Pendiente',
            'rejected' => 'Rechazado',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            default => 'Sin pago',
        };
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
