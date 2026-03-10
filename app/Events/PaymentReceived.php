<?php

namespace App\Events;

use App\Modules\Payments\Models\Installment;
use App\Modules\Payments\Models\Payment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The payment instance.
     *
     * @var \App\Modules\Payments\Models\Payment
     */
    public Payment $payment;

    /**
     * The installment instance.
     *
     * @var \App\Modules\Payments\Models\Installment
     */
    public Installment $installment;

    /**
     * Create a new event instance.
     */
    public function __construct(Payment $payment, Installment $installment)
    {
        $this->payment = $payment;
        $this->installment = $installment;
    }

    /**
     * Get the payment amount.
     */
    public function getAmount(): float
    {
        return (float) $this->payment->value;
    }

    /**
     * Get the remaining balance.
     */
    public function getRemainingBalance(): float
    {
        return (float) $this->installment->amount_outstanding;
    }

    /**
     * Check if installment is now fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->installment->isPaid();
    }
}
