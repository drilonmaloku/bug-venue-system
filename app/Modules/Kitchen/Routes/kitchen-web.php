<?php

declare(strict_types=1);

use App\Modules\Kitchen\Controllers\KitchenController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
});
