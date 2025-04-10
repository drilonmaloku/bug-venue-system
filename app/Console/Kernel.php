<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('notifications:delete-expired')->daily();
        $schedule->command('invoices:generate-monthly')->monthlyOn(1, '08:00'); // Run 1st day of month at 8:00 AM

        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        Commands\AddRole::class,
        \App\Console\Commands\GenerateReservationUuids::class,
    ];
}
