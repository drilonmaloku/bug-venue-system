<?php

namespace App\Modules\Notifications\Routes;

use App\Modules\Notifications\Controllers\NotificationsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'check.location.disabled','check.user.disabled'])->group(function () {
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');
    Route::get('/notifications/archive', [NotificationsController::class, 'archive'])->name('notifications.archive');
    Route::patch('/notifications/unread/{notification}', [NotificationsController::class, 'markNotificationAsUnread']);
    Route::post('/notifications/read/all', [NotificationsController::class, 'markAllNotificationsAsRead']);
    Route::patch('/notifications/read/{notification}', [NotificationsController::class, 'markNotificationAsRead']);
});
