<?php

namespace App\Events;

use App\Modules\Payments\Models\PaymentSchedule;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentScheduleCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The payment schedule instance.
     *
     * @var \App\Modules\Payments\Models\PaymentSchedule
     */
    public PaymentSchedule $schedule;

    /**
     * Create a new event instance.
     */
    public function __construct(PaymentSchedule $schedule)
    {
        $this->schedule = $schedule;
    }
}
