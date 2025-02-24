<?php

use App\Http\Controllers\UploadController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('landingPage.index');
})->name('landingPage');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');


Route::get('/login',function(){
    return view('formLogin');
})->name('login');

Route::get('/TabelDataInflasi', [UploadController::class, 'index'])->name('upload.index');
Route::get('/TabelDataInflasi/import', [UploadController::class, 'create'])->name('upload.create');
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
Route::get('/TabelDataInflasi/{data_name}', [UploadController::class, 'show'])->name('upload.show');

Route::get('/coba', function () {
    return view('coba');
});
