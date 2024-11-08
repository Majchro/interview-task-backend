<?php

declare(strict_types=1);

use App\Modules\Invoices\Api\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('{id}', [InvoiceController::class, 'show'])->name('show');
});
