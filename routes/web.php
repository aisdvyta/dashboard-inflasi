<?php

use App\Http\Controllers\UploadController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/login',function(){
    return view('formLogin');
})->name('login');
Route::get('/', function () {
    return view('user.index');
})->name('landingPage');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');

Route::get('/TabelDataInflasi', [UploadController::class, 'index'])->name('manajemen-data-inflasi.index');
Route::get('/TabelDataInflasi/import', [UploadController::class, 'create'])->name('manajemen-data-inflasi.create');
Route::post('/upload', [UploadController::class, 'store'])->name('manajemen-data-inflasi.store');
Route::get('/TabelDataInflasi/{data_name}', [UploadController::class, 'show'])->name('manajemen-data-inflasi.show');

// dashboard
Route::get('/dashboard/infkelompok',function(){return view('dashboard.infKelompok');})->name('dashboard.kelompok');
Route::get('/dashboard/infseries',function(){return view('dashboard.infSeries');})->name('dashboard.series');
Route::get('/dashboard/infspasial',function(){return view('dashboard.infSpasial');})->name('dashboard.spasial');
Route::get('/dashboard/infbulanan',function(){return view('dashboard.infBulananJatim');})->name('dashboard.bulanan');

// daftar tabel inflasi
Route::get('/user/DaftarTabelInflasi',function(){return view('user.daftar-tabel-inflasi.index');})->name('daftar-tabel-inlfasi.index');