<?php

use App\Http\Controllers\EnquiryController;
use Illuminate\Support\Facades\Route;

Route::post('/enquiries', [EnquiryController::class, 'store'])->name('api.enquiries.store');
