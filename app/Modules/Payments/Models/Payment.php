<?php

namespace App\Modules\Payments\Models;

use App\Modules\Clients\Models\Client;
use App\Modules\Reservations\Models\Reservation;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Payment method constants
    const METHOD_CASH = 1;
    const METHOD_BANK = 2;
    const METHOD_CREDIT_CARD = 3;
    const METHOD_DEBIT_CARD = 4;
    const METHOD_CHECK = 5;
    const METHOD_ONLINE = 6;
    const METHOD_OTHER = 7;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_PARTIAL_REFUND = 'partial_refund';
    const STATUS_CANCELLED = 'cancelled';

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }

    /**
     * Get the reservation associated with this payment.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the client associated with this payment.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the installment associated with this payment.
     */
    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }

    /**
     * Get payment method label attribute.
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        $methods = [
            self::METHOD_CASH => 'Cash',
            self::METHOD_BANK => 'Bank Transfer',
            self::METHOD_CREDIT_CARD => 'Credit Card',
            self::METHOD_DEBIT_CARD => 'Debit Card',
            self::METHOD_CHECK => 'Check',
            self::METHOD_ONLINE => 'Online Payment',
            self::METHOD_OTHER => 'Other',
        ];

        if (!$this->payment_method) {
            return 'Cash';
        }

        return $methods[$this->payment_method] ?? 'Cash';
    }

    /**
     * Get status label attribute.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_REFUNDED => 'Refunded',
            self::STATUS_PARTIAL_REFUND => 'Partial Refund',
            self::STATUS_CANCELLED => 'Cancelled',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status class attribute for UI.
     */
    public function getStatusClassAttribute(): string
    {
        $classes = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FAILED => 'danger',
            self::STATUS_REFUNDED => 'info',
            self::STATUS_PARTIAL_REFUND => 'info',
            self::STATUS_CANCELLED => 'secondary',
        ];

        return $classes[$this->status] ?? 'secondary';
    }

    /**
     * Check if payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Mark payment as refunded.
     */
    public function markRefunded(bool $partial = false): bool
    {
        return $this->update([
            'status' => $partial ? self::STATUS_PARTIAL_REFUND : self::STATUS_REFUNDED,
        ]);
    }

    /**
     * Scope for completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for payments in date range.
     */
    public function scopeInDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
