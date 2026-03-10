<?php

namespace App\Modules\Payments\Models;

use App\Models\User;
use App\Modules\Clients\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LatePaymentAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_schedule_id',
        'installment_id',
        'client_id',
        'severity',
        'status',
        'days_overdue',
        'amount_overdue',
        'alert_date',
        'acknowledged_by',
        'acknowledged_at',
        'resolution_notes',
        'resolved_at',
        'escalated_to',
        'escalated_at',
        'notified_at',
    ];

    protected $casts = [
        'alert_date' => 'date',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
        'escalated_at' => 'datetime',
        'notified_at' => 'datetime',
        'amount_overdue' => 'decimal:2',
        'days_overdue' => 'integer',
    ];

    // Severity constants
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_ACKNOWLEDGED = 'acknowledged';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_ESCALATED = 'escalated';
    const STATUS_IGNORED = 'ignored';

    /**
     * Get the payment schedule associated with this alert.
     */
    public function paymentSchedule(): BelongsTo
    {
        return $this->belongsTo(PaymentSchedule::class, 'payment_schedule_id');
    }

    /**
     * Get the installment associated with this alert.
     */
    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class, 'installment_id');
    }

    /**
     * Get the client associated with this alert.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user who acknowledged this alert.
     */
    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    /**
     * Get the user this alert was escalated to.
     */
    public function escalatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_to');
    }

    /**
     * Scope for active alerts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for alerts by severity.
     */
    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope for high priority alerts (high and critical).
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('severity', [self::SEVERITY_HIGH, self::SEVERITY_CRITICAL]);
    }

    /**
     * Scope for unresolved alerts.
     */
    public function scopeUnresolved($query)
    {
        return $query->whereNotIn('status', [self::STATUS_RESOLVED, self::STATUS_IGNORED]);
    }

    /**
     * Check if alert is resolved.
     */
    public function isResolved(): bool
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_IGNORED]);
    }

    /**
     * Acknowledge the alert.
     */
    public function acknowledge(int $userId): bool
    {
        return $this->update([
            'status' => self::STATUS_ACKNOWLEDGED,
            'acknowledged_by' => $userId,
            'acknowledged_at' => now(),
        ]);
    }

    /**
     * Resolve the alert.
     */
    public function resolve(?string $notes = null): bool
    {
        return $this->update([
            'status' => self::STATUS_RESOLVED,
            'resolution_notes' => $notes,
            'resolved_at' => now(),
        ]);
    }

    /**
     * Escalate the alert.
     */
    public function escalate(int $escalatedTo, ?string $notes = null): bool
    {
        return $this->update([
            'status' => self::STATUS_ESCALATED,
            'escalated_to' => $escalatedTo,
            'escalated_at' => now(),
            'resolution_notes' => $notes,
        ]);
    }

    /**
     * Mark as notified.
     */
    public function markNotified(): bool
    {
        return $this->update(['notified_at' => now()]);
    }

    /**
     * Get the severity label attribute.
     */
    public function getSeverityLabelAttribute(): string
    {
        $labels = [
            self::SEVERITY_LOW => 'Low',
            self::SEVERITY_MEDIUM => 'Medium',
            self::SEVERITY_HIGH => 'High',
            self::SEVERITY_CRITICAL => 'Critical',
        ];

        return $labels[$this->severity] ?? ucfirst($this->severity);
    }

    /**
     * Get the severity class attribute for UI.
     */
    public function getSeverityClassAttribute(): string
    {
        $classes = [
            self::SEVERITY_LOW => 'info',
            self::SEVERITY_MEDIUM => 'warning',
            self::SEVERITY_HIGH => 'danger',
            self::SEVERITY_CRITICAL => 'dark',
        ];

        return $classes[$this->severity] ?? 'secondary';
    }

    /**
     * Get the status label attribute.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_ACKNOWLEDGED => 'Acknowledged',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_ESCALATED => 'Escalated',
            self::STATUS_IGNORED => 'Ignored',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the status class attribute for UI.
     */
    public function getStatusClassAttribute(): string
    {
        $classes = [
            self::STATUS_ACTIVE => 'danger',
            self::STATUS_ACKNOWLEDGED => 'warning',
            self::STATUS_RESOLVED => 'success',
            self::STATUS_ESCALATED => 'dark',
            self::STATUS_IGNORED => 'secondary',
        ];

        return $classes[$this->status] ?? 'secondary';
    }

    /**
     * Calculate severity based on days overdue.
     */
    public static function calculateSeverity(int $daysOverdue): string
    {
        if ($daysOverdue >= 30) {
            return self::SEVERITY_CRITICAL;
        } elseif ($daysOverdue >= 15) {
            return self::SEVERITY_HIGH;
        } elseif ($daysOverdue >= 8) {
            return self::SEVERITY_MEDIUM;
        } else {
            return self::SEVERITY_LOW;
        }
    }
}
