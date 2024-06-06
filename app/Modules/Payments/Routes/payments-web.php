<?php

declare(strict_types=1);

use App\Modules\Payments\Controllers\PaymentsController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth'], function () {
Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');
Route::get('/payments/{id}', [PaymentsController::class, 'view'])->name('payments.view');
Route::delete('/payments/{id}', [PaymentsController::class, 'delete'])->name('payments.destroy');
});