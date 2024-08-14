<?php

declare(strict_types=1);

use App\Modules\Settings\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/contract', [SettingsController::class, 'index'])->name('location-settings.contract');
    Route::post('/save-contract', [SettingsController::class, 'save'])->name('location-settings.contract-save');
});
