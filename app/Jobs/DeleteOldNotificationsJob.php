<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteOldNotificationsJob
{
    use Dispatchable;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logCount = DB::table('notifications')
            ->where('created_at', '<', now()->subWeek())
            ->whereNotNull('read_at')
            ->delete();

        Log::info('Delete Notifications via DeleteOldNotificationsJob at ' . now(). ' notifications deleted:' .$logCount);
    }

}

