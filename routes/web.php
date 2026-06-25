<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProposalPdfController;

Route::get('/', function () {
    return view('welcome');
});
Route::get(
    '/proposal/{proposal}/pdf',
    ProposalPdfController::class
)->name('proposal.pdf');
