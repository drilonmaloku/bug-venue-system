<?php

declare(strict_types=1);

use App\Modules\LocationPayments\Controllers\LocationInvoiceController;
use App\Modules\LocationPayments\Controllers\LocationPaymentController;
use App\Modules\LocationPayments\Controllers\LocationCreditDepositController;
use App\Modules\LocationPayments\Controllers\LocationPaymentsController;
use Illuminate\Support\Facades\Route;

// Location Payments
Route::middleware(['auth'])->prefix('locations/{location}')->group(function () {
    // Invoices
    Route::get('/invoices', [LocationInvoiceController::class, 'index'])->name('location.invoices.index');
    Route::get('/invoices/create', [LocationInvoiceController::class, 'create'])->name('location.invoices.create');
    Route::post('/invoices', [LocationInvoiceController::class, 'store'])->name('location.invoices.store');
    Route::get('/invoices/{invoice}', [LocationInvoiceController::class, 'show'])->name('location.invoices.show');
    Route::get('/invoices/{invoice}/pdf', [LocationInvoiceController::class, 'downloadPDF'])->name('location.invoices.pdf');

    // Credit Deposits
    Route::get('/credit-deposits', [LocationCreditDepositController::class, 'index'])->name('location.credit-deposits.index');
    Route::get('/credit-deposits/create', [LocationCreditDepositController::class, 'create'])->name('location.credit-deposits.create');
    Route::post('/credit-deposits', [LocationCreditDepositController::class, 'store'])->name('location.credit-deposits.store');
    Route::get('/credit-deposits/{deposit}', [LocationCreditDepositController::class, 'show'])->name('location.credit-deposits.show');
    Route::get('/credit-deposits/{deposit}/pdf', [LocationCreditDepositController::class, 'downloadPDF'])->name('location.credit-deposits.pdf');
});

// Location Payments Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/location-payments', [LocationPaymentsController::class, 'index'])->name('location-payments.index');
    Route::get('/location-payments/{location}', [LocationPaymentsController::class, 'show'])->name('location-payments.show');
    Route::get('/location-payments/{location}/credit-deposits/create', [LocationPaymentsController::class, 'createCreditDeposit'])->name('location-payments.credit-deposits.create');
    Route::post('/location-payments/{location}/credit-deposits', [LocationPaymentsController::class, 'storeCreditDeposit'])->name('location-payments.credit-deposits.store');
});

// System Admin Routes
Route::middleware(['auth', 'role:system-admin'])->group(function () {
    Route::get('/location-payments/credit-deposit/create/{location_id}', [LocationPaymentsController::class, 'createCreditDeposit'])
        ->name('location-payments.credit-deposit.create');
    Route::post('/location-payments/credit-deposit/{location_id}', [LocationPaymentsController::class, 'storeCreditDeposit'])
        ->name('location-payments.credit-deposit.store');
    Route::get('/location-payments/credit-deposit/pdf/{location_id}', [LocationPaymentsController::class, 'generateCreditDepositPdf'])
        ->name('location-payments.credit-deposit.pdf');
}); 