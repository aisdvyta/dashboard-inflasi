<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.InfBulananJatim');
});

Route::get('/input', function () {
    return view('input.main');
});

Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
