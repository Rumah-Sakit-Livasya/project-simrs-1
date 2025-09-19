<?php

use App\Http\Controllers\API\MjknAntreanController;
use Illuminate\Support\Facades\Route;

Route::prefix('mjkn-ws')->middleware('mjkn.auth')->group(function () {
    // Endpoint ini tidak perlu token, middleware akan menangani auth user/pass
    Route::get('/token', [MjknAntreanController::class, 'generateToken'])->name('api.mjkn.token');

    // Endpoint yang membutuhkan token
    Route::post('/statusantrean', [MjknAntreanController::class, 'statusAntrean'])->name('api.mjkn.statusantrean');
    Route::post('/ambilantrean', [MjknAntreanController::class, 'ambilAntrean']);
    Route::post('/sisaantrean', [MjknAntreanController::class, 'sisaAntrean']);
    Route::post('/batalantrean', [MjknAntreanController::class, 'batalAntrean']); // Disesuaikan dari spec /antrean/batal
    Route::post('/checkin', [MjknAntreanController::class, 'checkIn']);
    Route::post('/infopasienbaru', [MjknAntreanController::class, 'infoPasienBaru']);
    Route::post('/jadwaloperasi', [MjknAntreanController::class, 'jadwalOperasiRs']);
    Route::post('/jadwaloperasipasien', [MjknAntreanController::class, 'jadwalOperasiPasien']);
});
