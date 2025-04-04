<?php

declare(strict_types=1);

use App\Modules\LocationPayments\Controllers\LocationInvoiceController;
use App\Modules\LocationPayments\Controllers\LocationCreditDepositController;
use App\Modules\LocationPayments\Controllers\LocationPaymentsController;
use Illuminate\Support\Facades\Route;

// Location Payments Routes
Route::prefix('location-payments')->group(function () {
    // System Admin Only Routes
    Route::middleware(['role:system-admin'])->group(function () {
        Route::get('/', [LocationPaymentsController::class, 'index'])->name('location-payments.index');
        Route::get('/{location}', [LocationPaymentsController::class, 'show'])->name('location-payments.show');
        
        // Main Location Payments Routes
        // Credit Deposits
        Route::get('/credit-deposits/all', [LocationCreditDepositController::class, 'index'])->name('location-payments.credit-deposits.all');
        Route::get('/{location}/credit-deposits/create', [LocationPaymentsController::class, 'createCreditDeposit'])->name('location-payments.credit-deposits.create');
        Route::post('/{location}/credit-deposits', [LocationPaymentsController::class, 'storeCreditDeposit'])->name('location-payments.credit-deposits.store');
        Route::get('/credit-deposit/pdf/{location_id}', [LocationPaymentsController::class, 'generateCreditDepositPdf'])->name('location-payments.credit-deposit.pdf');
        
        // Invoices
        Route::get('/invoices/all', [LocationInvoiceController::class, 'index'])->name('location-payments.invoices.all');
        Route::post('/invoices/generate-for-credit-deposits', [LocationInvoiceController::class, 'generateInvoicesForCreditDeposits'])->name('location-payments.invoices.generate-for-credit-deposits');
        
        // Credit Transactions
        Route::get('/credit-transactions/all', [LocationCreditDepositController::class, 'transactions'])->name('location-payments.credit-transactions.all');
    });

    // Location-specific routes with location access check
    Route::middleware(['auth', 'check.location'])->group(function () {
        Route::prefix('locations/{location}')->group(function () {
            // Payments
            Route::get('/payments', [LocationPaymentsController::class, 'payments'])->name('location.payments.index');
            
            // Invoices
            Route::get('/invoices', [LocationInvoiceController::class, 'index'])->name('location.invoices.index');
            Route::get('/invoices/create', [LocationInvoiceController::class, 'create'])->name('location.invoices.create');
            Route::post('/invoices', [LocationInvoiceController::class, 'store'])->name('location.invoices.store');
            Route::get('/invoices/{invoice}', [LocationInvoiceController::class, 'show'])->name('location.invoices.show');
            Route::get('/invoices/{invoice}/pdf', [LocationInvoiceController::class, 'downloadPDF'])->name('location.invoices.pdf');
            Route::get('/invoices/{invoice}/process', [LocationInvoiceController::class, 'process'])->name('location.invoices.process');
            Route::post('/invoices/{invoice}/process', [LocationInvoiceController::class, 'processStore'])->name('location.invoices.process.store');
            Route::post('/invoices/{invoice}/mark-as-paid', [LocationInvoiceController::class, 'markAsPaid'])->name('location.invoices.mark-as-paid');

            // Credit Deposits
            Route::get('/credit-deposits', [LocationCreditDepositController::class, 'index'])->name('location.credit-deposits.index');
            Route::get('/credit-deposits/create', [LocationCreditDepositController::class, 'create'])->name('location.credit-deposits.create');
            Route::post('/credit-deposits', [LocationCreditDepositController::class, 'store'])->name('location.credit-deposits.store');
            Route::get('/credit-deposits/{deposit}', [LocationCreditDepositController::class, 'show'])->name('location.credit-deposits.show');
            Route::post('/credit-deposits/{deposit}/mark-as-completed', [LocationCreditDepositController::class, 'markAsCompleted'])->name('location.credit-deposits.mark-as-completed');

            // Credit Transactions
            Route::get('/credit-transactions', [LocationCreditDepositController::class, 'transactions'])->name('credit-transactions.index');
        });
    });
});