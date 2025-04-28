<?php

namespace App\Console\Commands;

use App\Modules\Reminders\Jobs\DeleteOldRemindersJob;
use Illuminate\Console\Command;

class DeleteOldRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old sent reminders that are older than 3 months';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching job to delete old reminders...');
        DeleteOldRemindersJob::dispatch();
        $this->info('Job dispatched successfully.');
    }
} 