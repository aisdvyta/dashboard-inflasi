<?php

use App\Http\Controllers\UploadController;
use App\Http\Controllers\ChartController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChartController::class, 'index'])->name('dashboard.index');

Route::get('/TabelDataInflasi', [UploadController::class, 'index'])->name('upload.index');
Route::get('/TabelDataInflasi/import', [UploadController::class, 'create'])->name('upload.create');
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
Route::get('/TabelDataInflasi/{data_name}', [UploadController::class, 'show'])->name('upload.show');
