<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Modules\Clients\Controllers\ClientsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes
Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');

// Legacy API Routes (for backward compatibility)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/clients', [ClientsController::class, 'getClients']);

// Load Versioned API Routes
require __DIR__ . '/api/v1.php';
