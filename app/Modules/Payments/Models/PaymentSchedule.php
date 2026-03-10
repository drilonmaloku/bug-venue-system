<?php

namespace App\Modules\Payments\Models;

use App\Models\User;
use App\Modules\Clients\Models\Client;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'client_id',
        'template_type',
        'total_amount',
        'paid_amount',
        'total_outstanding',
        'currency',
        'status',
        'event_date',
        'grace_period_days',
        'late_fees_enabled',
        'late_fee_percentage',
        'late_fee_cap',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'approved_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'total_outstanding' => 'decimal:2',
        'late_fee_percentage' => 'decimal:4',
        'late_fee_cap' => 'decimal:2',
        'late_fees_enabled' => 'boolean',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_SUSPENDED = 'suspended';

    // Template type constants
    const TEMPLATE_STANDARD_3_TIER = 'standard_3_tier';
    const TEMPLATE_EQUAL_SPLIT = 'equal_split';
    const TEMPLATE_FULL_UPFRONT = 'full_upfront';
    const TEMPLATE_CORPORATE_60_DAY = 'corporate_60_day';
    const TEMPLATE_MONTHLY_6 = 'monthly_6';
    const TEMPLATE_CUSTOM = 'custom';

    /**
     * Get the reservation associated with this payment schedule.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the client associated with this payment schedule.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user who created this schedule.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this schedule.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the installments for this schedule.
     */
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class, 'payment_schedule_id');
    }

    /**
     * Get the late payment alerts for this schedule.
     */
    public function latePaymentAlerts(): HasMany
    {
        return $this->hasMany(LatePaymentAlert::class, 'payment_schedule_id');
    }

    /**
     * Scope for active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for draft schedules.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Check if schedule is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->total_outstanding <= 0;
    }

    /**
     * Get payment progress percentage.
     */
    public function getProgressPercentage(): float
    {
        if ($this->total_amount <= 0) {
            return 100;
        }
        return round(($this->paid_amount / $this->total_amount) * 100, 2);
    }

    /**
     * Get the status label attribute.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_SUSPENDED => 'Suspended',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the status class attribute for UI.
     */
    public function getStatusClassAttribute(): string
    {
        $classes = [
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_ACTIVE => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_SUSPENDED => 'warning',
        ];

        return $classes[$this->status] ?? 'secondary';
    }

    /**
     * Activate the payment schedule.
     */
    public function activate(int $approvedBy): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_ACTIVE,
            'approved_by' => $approvedBy,
            'approved_at' => now(),
        ]);
    }

    /**
     * Mark as completed.
     */
    public function markCompleted(): bool
    {
        if (!$this->isFullyPaid()) {
            return false;
        }

        return $this->update(['status' => self::STATUS_COMPLETED]);
    }

    /**
     * Update paid amounts based on installments.
     */
    public function recalculateTotals(): bool
    {
        $totalPaid = $this->installments()->sum('paid_amount');
        $totalOutstanding = $this->total_amount - $totalPaid;

        return $this->update([
            'paid_amount' => $totalPaid,
            'total_outstanding' => max(0, $totalOutstanding),
        ]);
    }
}
