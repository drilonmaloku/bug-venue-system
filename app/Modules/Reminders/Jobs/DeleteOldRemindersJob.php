<?php

namespace App\Modules\Reminders\Jobs;

use App\Modules\Reminders\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeleteOldRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $threeMonthsAgo = now()->subMonths(3);
            Log::info('Current time: ' . now()->format('Y-m-d H:i:s'));
            Log::info('Looking for reminders older than: ' . $threeMonthsAgo->format('Y-m-d H:i:s'));
            
            $reminders = Reminder::where('is_sent', true)->get();
            Log::info('Total sent reminders found: ' . $reminders->count());
            
            foreach ($reminders as $reminder) {
                $createdAt = Carbon::parse($reminder->created_at);
                $isOlder = $createdAt->lt($threeMonthsAgo);
                Log::info("Reminder ID: {$reminder->id}, Created: {$reminder->created_at}, Is older than 3 months: " . ($isOlder ? 'Yes' : 'No'));
            }
            
            $remindersToDelete = $reminders->filter(function($reminder) use ($threeMonthsAgo) {
                return Carbon::parse($reminder->created_at)->lt($threeMonthsAgo);
            });
                
            Log::info('Found ' . $remindersToDelete->count() . ' reminders to delete');
            
            foreach ($remindersToDelete as $reminder) {
                Log::info('Deleting reminder ID: ' . $reminder->id . ' created at: ' . $reminder->created_at);
            }
            
            $deletedCount = $remindersToDelete->count();
            Reminder::whereIn('id', $remindersToDelete->pluck('id'))->delete();

            Log::info("Successfully deleted {$deletedCount} old sent reminders older than 3 months");
        } catch (\Exception $e) {
            Log::error('Error deleting old reminders: ' . $e->getMessage());
            throw $e;
        }
    }
} 