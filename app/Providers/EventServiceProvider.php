<?php

namespace App\Providers;

use App\Events\PaymentOverdue;
use App\Events\PaymentReceived;
use App\Events\PaymentScheduleCreated;
use App\Listeners\CreateLatePaymentAlert;
use App\Listeners\UpdatePaymentStatus;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Observers\ReservationObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PaymentReceived::class => [
            UpdatePaymentStatus::class,
        ],
        PaymentOverdue::class => [
            CreateLatePaymentAlert::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Reservation::observe(ReservationObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
