<?php

declare(strict_types=1);

use App\Modules\Menus\Controllers\MenuController;
use Illuminate\Support\Facades\Route;


// Menus
Route::group(['middleware' => 'auth'], function () {
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/export', [MenuController::class, 'export'])->name('menus.export');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::get('/menus/{id}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::get('/menus/{id}', [MenuController::class, 'view'])->name('menus.view');
    Route::put('/menus/{id}/update', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{id}', [MenuController::class, 'delete'])->name('menus.destroy');

});
