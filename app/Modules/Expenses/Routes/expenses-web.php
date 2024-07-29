<?php

declare(strict_types=1);

use App\Modules\Expenses\Controllers\ExpensesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/expenses', [ExpensesController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/export', [ExpensesController::class, 'export'])->name('expenses.export');
    Route::get('/expenses/create', [ExpensesController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpensesController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{id}/edit', [ExpensesController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{id}/update', [ExpensesController::class, 'update'])->name('expenses.update');
    Route::get('/expenses/{id}', [ExpensesController::class, 'view'])->name('expenses.view');
    Route::delete('/expenses/{id}', [ExpensesController::class, 'destroy'])->name('expenses.destroy');

});