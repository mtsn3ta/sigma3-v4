<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RFID\SchoolRfidController;

Route::post(
    '/rfid/sekolah',
    SchoolRfidController::class
);
