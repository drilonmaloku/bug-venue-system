<?php

declare(strict_types=1);

use App\Modules\Clients\Controllers\ClientsController;
use App\Modules\Onboard\Controllers\OnboardController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/onboard', [OnboardController::class, 'index'])->name('onboard.index');
    Route::post('/onboard', [OnboardController::class, 'store'])->name('onboard.store');
});
