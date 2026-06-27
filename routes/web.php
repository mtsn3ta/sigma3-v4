<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProposalPdfController;
use App\Http\Controllers\LaporanKegiatanPdfController;
use App\Http\Controllers\LaporanBulananPdfController;

Route::get('/', function () {
    return view('welcome');
});
Route::get(
    '/proposal/{proposal}/pdf',
    ProposalPdfController::class
)->name('proposal.pdf');
Route::get(
    '/laporan-kegiatan/{laporan}/pdf',
    LaporanKegiatanPdfController::class
)->name('laporan-kegiatan.pdf');
Route::get(
    '/laporan-bulanan/{laporanBulanan}/pdf',
    LaporanBulananPdfController::class
)->name('laporan-bulanan.pdf');
