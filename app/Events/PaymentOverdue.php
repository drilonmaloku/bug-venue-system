<?php

namespace App\Events;

use App\Modules\Payments\Models\Installment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentOverdue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The installment instance.
     *
     * @var \App\Modules\Payments\Models\Installment
     */
    public Installment $installment;

    /**
     * Number of days overdue.
     *
     * @var int
     */
    public int $daysOverdue;

    /**
     * Create a new event instance.
     */
    public function __construct(Installment $installment, int $daysOverdue)
    {
        $this->installment = $installment;
        $this->daysOverdue = $daysOverdue;
    }

    /**
     * Get the severity level based on days overdue.
     */
    public function getSeverity(): string
    {
        return \App\Modules\Payments\Models\LatePaymentAlert::calculateSeverity($this->daysOverdue);
    }

    /**
     * Get the amount overdue.
     */
    public function getAmountOverdue(): float
    {
        return (float) ($this->installment->amount_outstanding + $this->installment->late_fees_accrued);
    }
}
