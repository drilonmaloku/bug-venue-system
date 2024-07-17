<?php

declare(strict_types=1);

use App\Modules\Reports\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;


// Reports
Route::group(['middleware' => ['auth', 'role:super-admin|system-admin']], function () {
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports-generated', [ReportsController::class, 'generate'])->name('reports.generate');
});
