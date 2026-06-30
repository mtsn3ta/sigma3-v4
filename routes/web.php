<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProposalPdfController;
use App\Http\Controllers\LaporanKegiatanPdfController;
use App\Http\Controllers\LaporanBulananPdfController;
use App\Http\Controllers\Thermal\IzinKeluarPrintController;
use App\Http\Controllers\Thermal\TugasGuruIzinPrintController;
use App\Http\Controllers\PrintKonselingController;
use App\Http\Controllers\PrintPermasalahanController;
use App\Http\Controllers\PrintMonitoringJurnalController;
use App\Http\Controllers\PrintMonitoringTugasGuruIzinController;
use App\Http\Controllers\PrintMonitoringIzinKeluarController;

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
Route::middleware(['auth'])->group(function () {

    Route::get(

        '/thermal/izin-keluar/{izinKeluar}',

        IzinKeluarPrintController::class

    )->name('thermal.izin-keluar');

    Route::get(

        '/thermal/tugas-guru-izin/{tugasGuruIzin}',

        TugasGuruIzinPrintController::class

    )->name('thermal.tugas-guru-izin');
});
Route::middleware(['auth'])
    ->get(
        '/bk/konseling/{siswa}/print',
        PrintKonselingController::class
    )
    ->name('konseling.print');
Route::middleware(['auth'])
    ->get(
        '/bk/permasalahan/{siswa}/print',
        PrintPermasalahanController::class
    )
    ->name('permasalahan.print');
Route::get(
    '/print/monitoring-jurnal',
    PrintMonitoringJurnalController::class
)->name('print.monitoring-jurnal');
Route::get(
    '/print/monitoring-tugas-guru-izin',
    PrintMonitoringTugasGuruIzinController::class
)->name('print.monitoring-tugas-guru-izin');
Route::get(
    '/print/monitoring-izin-keluar',
    PrintMonitoringIzinKeluarController::class
)->name('print.monitoring-izin-keluar');
