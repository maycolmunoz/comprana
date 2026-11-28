<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory, MassPrunable;

    protected $fillable = [
        'customer_id',
        'dispatcher_id',
        'delivery_id',
        'invoice',
        'total',
        'items',
        'phone',
        'address',
        'status',
        'payment_id',
        'payment_status',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'total' => 'int',
        'items' => 'array',
    ];

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::whereNull('payment_id')
            ->where('created_at', '<', now()->subMonth());
    }

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

    public function invoiceFileName(): string
    {
        return 'factura-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT).'-'.($this->created_at ?? now())->format('Y-m-d').'.pdf';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'approved';
    }

    public function isPaymentPending(): bool
    {
        return filled($this->payment_id)
            && in_array($this->payment_status, ['pending', 'in_process'], true);
    }

    public function canRetryPayment(): bool
    {
        if (in_array($this->payment_status, ['pending', 'in_process'], true)) {
            return blank($this->payment_id);
        }

        return in_array($this->payment_status, [null, 'rejected', 'cancelled'], true);
    }

    public function paymentStatusLabel(): string
    {
        return self::paymentStatusLabelFor($this->payment_status);
    }

    public static function paymentStatusLabelFor(?string $status): string
    {
        return match ($status) {
            'approved' => 'Pagado',
            'pending', 'in_process' => 'Pendiente',
            'rejected' => 'Rechazado',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            'charged_back' => 'Contracargo',
            'in_mediation' => 'En mediación',
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
