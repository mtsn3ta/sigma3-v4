<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RFID\SchoolRfidController;
use App\Http\Controllers\Api\RFID\IbadahRfidController;
use App\Http\Controllers\API\RFID\EkstrakurikulerRfidController;

Route::post(
    '/rfid/sekolah',
    SchoolRfidController::class
);
Route::post(
    '/rfid/ibadah',
    IbadahRfidController::class,
);
Route::post(
    '/rfid/ekskul',
    [EkstrakurikulerRfidController::class, 'scan']
);
