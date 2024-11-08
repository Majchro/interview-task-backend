<?php

declare(strict_types=1);

use App\Modules\Invoices\Api\Controllers\ApproveInvoiceController;
use App\Modules\Invoices\Api\Controllers\InvoiceController;
use App\Modules\Invoices\Api\Controllers\RejectInvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('{id}', [InvoiceController::class, 'show'])->name('show');
    Route::put('{id}/approve', ApproveInvoiceController::class)->name('approve');
    Route::put('{id}/reject', RejectInvoiceController::class)->name('reject');
});
