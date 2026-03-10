<?php

namespace App\Modules\Payments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Installment extends Model
{
    use HasFactory;

    protected $table = 'payment_installments';

    protected $fillable = [
        'payment_schedule_id',
        'type',
        'sequence',
        'name',
        'description',
        'amount',
        'percentage_of_total',
        'due_date',
        'status',
        'paid_amount',
        'amount_outstanding',
        'paid_date',
        'overdue_since',
        'late_fees_accrued',
        'reminder_sent_at',
        'overdue_notice_sent_at',
        'final_notice_sent_at',
        'reminder_count',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'overdue_since' => 'date',
        'reminder_sent_at' => 'datetime',
        'overdue_notice_sent_at' => 'datetime',
        'final_notice_sent_at' => 'datetime',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'amount_outstanding' => 'decimal:2',
        'late_fees_accrued' => 'decimal:2',
        'percentage_of_total' => 'decimal:2',
    ];

    // Type constants
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_INTERIM = 'interim';
    const TYPE_FINAL = 'final';
    const TYPE_MILESTONE = 'milestone';
    const TYPE_CUSTOM = 'custom';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PARTIAL = 'partial';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_WAIVED = 'waived';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the payment schedule associated with this installment.
     */
    public function paymentSchedule(): BelongsTo
    {
        return $this->belongsTo(PaymentSchedule::class, 'payment_schedule_id');
    }

    /**
     * Get the payments associated with this installment.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'installment_id');
    }

    /**
     * Get the late payment alerts for this installment.
     */
    public function latePaymentAlerts(): HasMany
    {
        return $this->hasMany(LatePaymentAlert::class, 'installment_id');
    }

    /**
     * Scope for pending installments.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for overdue installments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }

    /**
     * Scope for paid installments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope for upcoming installments (due within days).
     */
    public function scopeUpcoming($query, int $days = 14)
    {
        $startDate = now();
        $endDate = now()->addDays($days);

        return $query->whereBetween('due_date', [$startDate, $endDate])
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PARTIAL]);
    }

    /**
     * Check if installment is fully paid.
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID || $this->amount_outstanding <= 0;
    }

    /**
     * Check if installment is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE || 
            ($this->due_date < now()->startOfDay() && !$this->isPaid());
    }

    /**
     * Calculate days until due.
     */
    public function daysUntilDue(): int
    {
        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Calculate days overdue.
     */
    public function daysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return $this->due_date->diffInDays(now());
    }

    /**
     * Record a payment against this installment.
     */
    public function recordPayment(float $amount): bool
    {
        $newPaidAmount = $this->paid_amount + $amount;
        $newOutstanding = max(0, $this->amount - $newPaidAmount);

        $status = $newOutstanding <= 0 ? self::STATUS_PAID : 
            ($newPaidAmount > 0 ? self::STATUS_PARTIAL : self::STATUS_PENDING);

        $updateData = [
            'paid_amount' => $newPaidAmount,
            'amount_outstanding' => $newOutstanding,
            'status' => $status,
        ];

        if ($newOutstanding <= 0) {
            $updateData['paid_date'] = now();
        }

        return $this->update($updateData);
    }

    /**
     * Mark as overdue.
     */
    public function markOverdue(): bool
    {
        return $this->update([
            'status' => self::STATUS_OVERDUE,
            'overdue_since' => now(),
        ]);
    }

    /**
     * Get the status label attribute.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PARTIAL => 'Partially Paid',
            self::STATUS_PAID => 'Paid',
            self::STATUS_OVERDUE => 'Overdue',
            self::STATUS_WAIVED => 'Waived',
            self::STATUS_CANCELLED => 'Cancelled',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the status class attribute for UI.
     */
    public function getStatusClassAttribute(): string
    {
        $classes = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_PARTIAL => 'info',
            self::STATUS_PAID => 'success',
            self::STATUS_OVERDUE => 'danger',
            self::STATUS_WAIVED => 'secondary',
            self::STATUS_CANCELLED => 'secondary',
        ];

        return $classes[$this->status] ?? 'secondary';
    }

    /**
     * Get the type label attribute.
     */
    public function getTypeLabelAttribute(): string
    {
        $labels = [
            self::TYPE_DEPOSIT => 'Deposit',
            self::TYPE_INTERIM => 'Interim Payment',
            self::TYPE_FINAL => 'Final Payment',
            self::TYPE_MILESTONE => 'Milestone Payment',
            self::TYPE_CUSTOM => 'Custom Payment',
        ];

        return $labels[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get total due amount (including late fees).
     */
    public function getTotalDueAttribute(): float
    {
        return $this->amount_outstanding + $this->late_fees_accrued;
    }

    /**
     * Check if reminder should be sent.
     */
    public function shouldSendReminder(int $daysBefore = 7): bool
    {
        if ($this->isPaid() || $this->isOverdue()) {
            return false;
        }

        $reminderDate = $this->due_date->copy()->subDays($daysBefore);
        $today = now()->startOfDay();

        return $today->gte($reminderDate) && $today->lt($this->due_date) && 
            ($this->reminder_sent_at === null || $this->reminder_sent_at->lt($today));
    }
}
