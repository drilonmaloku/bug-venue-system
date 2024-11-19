<?php

declare(strict_types=1);

use App\Modules\Collaborators\Controllers\CollaboratorsController;
use Illuminate\Support\Facades\Route;


// Collaborators
Route::group(['middleware' => 'auth'], function () {
    Route::get('/collaborators', [CollaboratorsController::class, 'index'])->name('collaborators.index');
    Route::post('/collaborators', [CollaboratorsController::class, 'store'])->name('collaborators.store');
    Route::get('/collaborators/create', [CollaboratorsController::class, 'create'])->name('collaborators.create');
    Route::get('/collaborators/{id}/edit', [CollaboratorsController::class, 'edit'])->name('collaborators.edit');
    Route::get('/collaborators/{id}', [CollaboratorsController::class, 'view'])->name('collaborators.view');
    Route::put('/collaborators/{id}/update', [CollaboratorsController::class, 'update'])->name('collaborators.update');
    Route::delete('/collaborators/{id}', [CollaboratorsController::class, 'delete'])->name('collaborators.destroy');

});
