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
        \App\Console\Commands\ProcessRemindersCommand::class,
        \App\Console\Commands\DeleteOldRemindersCommand::class,
    ];
}
