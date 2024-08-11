<?php

declare(strict_types=1);

use App\Modules\Settings\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('location-settings.index');
    Route::post('/settings', [SettingsController::class, 'save'])->name('location-settings.save');
});
