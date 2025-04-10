<?php

namespace App\Providers;

use App\Modules\Reminders\Models\Reminder;
use App\Modules\Reminders\Observers\ReminderObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        
        // Register observers
        Reminder::observe(ReminderObserver::class);
    }
}
