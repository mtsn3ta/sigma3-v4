<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProposalPdfController;
use App\Http\Controllers\LaporanKegiatanPdfController;

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
