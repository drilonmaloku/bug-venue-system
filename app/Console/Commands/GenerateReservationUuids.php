<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateReservationUuids extends Command
{
    protected $signature = 'reservations:generate-uuids';
    protected $description = 'Generate UUIDs for existing reservations';

    public function handle()
    {
        $this->info('Starting UUID generation for reservations...');

        try {
            // Direct database update to bypass observers and events
            $affected = DB::table('reservations')
                ->whereNull('uuid')
                ->orWhere('uuid', '')
                ->update([
                    'uuid' => DB::raw('UUID()')
                ]);

            $this->info("Successfully generated UUIDs for {$affected} reservations.");

        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
} 