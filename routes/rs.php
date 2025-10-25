<?php

use App\Http\Controllers\RS\DocumentController;
use App\Http\Controllers\RS\DocumentTypeController;
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
});
