<?php

declare(strict_types=1);

use App\Modules\Common\Controllers\BackupController;
use App\Modules\Common\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


// Backup
Route::middleware(['middleware' => 'auth'])->group(function () {
    Route::get('/backup', [BackupController::class, 'index'])->name('common.backup');
    Route::post('/save-title-template', [DashboardController::class, 'saveTitleTemplate'])->name('save.title.template'); 
    Route::get('/backup-db', [BackupController::class, 'createDatabaseBackup'])->name('common.db-backup');
    Route::get('/download-db-backup/{file}', [BackupController::class, 'downloadDatabaseBackup'])->name('common.db-backup-download');
    Route::get('/delete-db-backup/{file}', [BackupController::class, 'deleteDatabaseBackup'])->name('common.db-backup-delete');
});