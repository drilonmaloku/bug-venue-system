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
        $schedule->command('reminders:process')->everyFiveMinutes();
        $schedule->command('reminders:cleanup')->daily();
        $schedule->command('backup:database-to-drive')->dailyAt("00:51");
        
        // Late Payments Tracking System - Scheduled Commands
        // Run daily at 6 AM to check for late payments
        $schedule->command('payments:check-late --auto-resolve')->dailyAt('06:00');
        
        // Send payment reminders 7 days before due date
        $schedule->command('payments:send-reminders --days=7')->dailyAt('09:00');
        
        // Send payment reminders 1 day before due date (final reminder)
        $schedule->command('payments:send-reminders --days=1')->dailyAt('10:00');
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
        \App\Console\Commands\ProcessRemindersCommand::class,
        \App\Console\Commands\DeleteOldRemindersCommand::class,
        \App\Console\Commands\CheckLatePayments::class,
        \App\Console\Commands\SendPaymentReminders::class,
    ];
}
