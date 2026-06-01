<?php

use App\Http\Controllers\ReceiptPrintController;
use App\Http\Controllers\PublicTrackingController;
use App\Models\Manifest;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(Authenticate::class)->group(function (): void {
    Route::get('/admin/shipments/{shipment}/receipt', ReceiptPrintController::class)
        ->name('shipments.receipt.print');
    Route::get('/admin/manifests/{manifest}/print', function (Manifest $manifest) {
        return view('manifests.print', [
            'manifest' => $manifest->load(['route', 'originAdmin', 'destinationAgent', 'items.shipment']),
        ]);
    })->name('manifests.print');
});

Route::get('/tracking', [PublicTrackingController::class, 'index'])->name('tracking.index');
Route::get('/tracking/{receiptNumber}', [PublicTrackingController::class, 'showByReceipt'])->name('tracking.show');
Route::get('/t/{token}', [PublicTrackingController::class, 'showByToken'])->name('tracking.token');
