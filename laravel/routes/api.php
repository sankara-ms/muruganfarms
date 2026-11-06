<?php

use App\Http\Controllers\EnquiryController;
use Illuminate\Support\Facades\Route;

Route::post('/enquiries', [EnquiryController::class, 'store'])->name('api.enquiries.store');

// The visit count is stored by the published Function's database, which this dev
// server cannot reach. Answering in the Function's shape keeps the page's
// "hide the number we cannot vouch for" path exercised locally too.
Route::post('/visits', fn () => response()->json(['ok' => false, 'error' => 'counter_offline'], 503))
    ->name('api.visits.store');
