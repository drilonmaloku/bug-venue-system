<?php

declare(strict_types=1);

use App\Modules\SeatingPlans\Controllers\SeatingPlanController;
use Illuminate\Support\Facades\Route;

// Seating Plans
Route::group(['middleware' => 'auth'], function () {
    Route::get('/seating-plans', [SeatingPlanController::class, 'index'])->name('seating-plans.index');
    Route::post('/seating-plans', [SeatingPlanController::class, 'store'])->name('seating-plans.store');
    Route::get('/seating-plans/create', [SeatingPlanController::class, 'create'])->name('seating-plans.create');
    Route::get('/seating-plans/{id}/edit', [SeatingPlanController::class, 'edit'])->name('seating-plans.edit');
    Route::get('/seating-plans/{id}', [SeatingPlanController::class, 'view'])->name('seating-plans.view');
    Route::put('/seating-plans/{id}/update', [SeatingPlanController::class, 'update'])->name('seating-plans.update');
    Route::delete('/seating-plans/{id}', [SeatingPlanController::class, 'delete'])->name('seating-plans.destroy');
}); 