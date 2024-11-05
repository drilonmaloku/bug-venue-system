<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeleteOldNotifications extends Command
{
    protected $signature = 'notifications:delete-expired';
    protected $description = 'Delete notifications older than 30 days';


    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(30);

        DB::table('notifications')->where('created_at', '<', $cutoffDate)->delete();

        $this->info('Expired notifications deleted successfully.');
    }
}
