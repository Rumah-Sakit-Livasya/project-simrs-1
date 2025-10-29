<?php

use App\Http\Controllers\RS\DocumentController;
use App\Http\Controllers\RS\DocumentTypeController;
use App\Http\Controllers\RS\InspectionLogController;
use App\Http\Controllers\RS\MaterialApprovalController;
use App\Http\Controllers\RS\ProjectBuildItemController;
use App\Http\Controllers\RS\StockManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something
*/

Route::middleware(['auth'])->group(function () {
    Route::resource('documents', DocumentController::class);
    Route::resource('document-types', DocumentTypeController::class);

    // Jika Anda ingin URL spesifik untuk upload revisi (opsional, tapi praktik bagus)
    Route::post('documents/{document}/revisions', [DocumentController::class, 'storeRevision'])->name('documents.storeRevision');

    // Rute khusus untuk preview di popup window
    Route::get('documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');

    Route::resource('material-approvals', MaterialApprovalController::class);
    // NEW ROUTE for the review page
    Route::get('material-approvals/{materialApproval}/review', [MaterialApprovalController::class, 'review'])->name('material-approvals.review');

    // NEW ROUTE to handle the approval/rejection action
    Route::post('material-approvals/{materialApproval}/process-review', [MaterialApprovalController::class, 'processReview'])->name('material-approvals.processReview');

    Route::resource('inspection-logs', InspectionLogController::class);

    // Route::get('project-build-items', [ProjectBuildItemController::class, 'index'])->name('project-build-items.index');

    Route::resource('project-build-items', ProjectBuildItemController::class);

    Route::get('stock-management', [StockManagementController::class, 'index'])->name('stock-management.index');

    // Rute baru untuk detail stok per gudang
    Route::get('stock-management/{projectBuildItem}/details', [StockManagementController::class, 'getStockDetails'])->name('stock-management.details');

    Route::get('stock-management/{projectBuildItem}/card', [StockManagementController::class, 'showStockCard'])->name('stock-management.card');

    Route::post('stock-management/manual-stock-in', [StockManagementController::class, 'manualStockIn'])->name('stock-management.manualStockIn');
});
