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
use App\Http\Controllers\PrintMonitoringPermasalahanController;
use App\Http\Controllers\PrintMonitoringEkstrakurikulerController;
use App\Http\Controllers\Frontend\LandingController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\EkstrakurikulerController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MonitoringAttendanceController;
use App\Http\Controllers\PengumumanController;

Route::get('/', LandingController::class)
    ->name('landing');
Route::get('/prestasi', [PrestasiController::class, 'index'])
    ->name('prestasi');
Route::get('/galeri', [GalleryController::class, 'index'])
    ->name('galeri');
Route::get(
    '/monitoring',
    [MonitoringAttendanceController::class, 'index']
)->name('monitoring');
Route::get(
    '/pengumuman/{pengumuman}',
    PengumumanController::class
)->name('frontend.pengumuman.show');
Route::get(
    '/ekstrakurikuler',
    [EkstrakurikulerController::class, 'index']
)->name('ekstrakurikuler');
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
Route::get(
    '/print/monitoring-permasalahan',
    PrintMonitoringPermasalahanController::class
)->name('print.monitoring-permasalahan');
Route::get(
    '/print/monitoring-ekstrakurikuler',
    PrintMonitoringEkstrakurikulerController::class
)->name('print.monitoring-ekstrakurikuler');
