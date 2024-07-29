<?php

declare(strict_types=1);

use App\Modules\Users\Controllers\UsersController;
use Illuminate\Support\Facades\Route;


// Users
Route::group(['middleware' => 'auth'], function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::get('/users/export', [UsersController::class, 'export'])->name('users.export');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UsersController::class, 'view'])->name('users.view');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}/update', [UsersController::class, 'update'])->name('users.update');
    Route::put('/users/{id}/update-password-profile', [UsersController::class, 'updatePassword'])->name('users-password.update');
    Route::delete('//users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');


});