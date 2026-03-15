<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/assets/scan', [AssetController::class, 'scan'])->name('assets.scan');
Route::get('/assets/lookup', [AssetController::class, 'lookup'])->name('assets.lookup');
Route::post('/assets/bulk-barcode', [AssetController::class, 'bulkBarcode'])->name('assets.bulk-barcode');
Route::get('/assets/{asset}/barcode', [AssetController::class, 'barcode'])->name('assets.barcode');
Route::resource('assets', AssetController::class);

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');