<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class BackupDatabaseToDrive extends Command
{
    protected $signature = 'backup:database-to-drive';
    protected $description = 'Create a database backup and upload it to Google Drive';

    public function handle()
    {
        $this->info("Creating database backup...");

        // Run only the database backup
        Artisan::call('backup:run', ['--only-db' => true]);

        $this->info("Backup created. Uploading to Google Drive...");

        $backupPath = storage_path('/app/VMS');
        $latestBackup = collect(scandir($backupPath))
            ->filter(fn($file) => str_ends_with($file, '.zip'))
            ->sortDesc()
            ->first();

        if (!$latestBackup) {
            $this->error('No backup file found.');
            return;
        }

        $filePath = $backupPath . '/' . $latestBackup;

        // Upload to Google Drive
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google-drive-service-account.json'));
        $client->addScope(Google_Service_Drive::DRIVE);

        $service = new Google_Service_Drive($client);

        $file = new Google_Service_Drive_DriveFile([
            'name' => $latestBackup,
            'parents' => [env('GOOGLE_DRIVE_FOLDER_ID')],
        ]);

        $service->files->create($file, [
            'data' => file_get_contents($filePath),
            'mimeType' => 'application/zip',
            'uploadType' => 'multipart',
        ]);

        $this->info("Upload complete: $latestBackup");
    }
}
