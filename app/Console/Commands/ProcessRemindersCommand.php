<?php

namespace App\Console\Commands;

use App\Modules\Reminders\Services\RemindersService;
use Illuminate\Console\Command;

class ProcessRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process due reminders and send notifications';

    /**
     * Execute the console command.
     */
    public function handle(RemindersService $remindersService)
    {
        $this->info('Processing due reminders...');
        
        $remindersService->processDueReminders();
        
        $this->info('Reminders processed successfully.');
        
        return Command::SUCCESS;
    }
}
