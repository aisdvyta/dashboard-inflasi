<?php

use App\Http\Controllers\UploadController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');

// USER //
Route::get('/', function () {return view('user.index');})->name('landingPage');
Route::get('/DaftarTabelInflasi',function(){return view('user.daftar-tabel-inflasi.index');})->name('daftar-tabel-inlfasi.index');

// MANAJEMEN DATA INFLASI //
Route::get('/TabelDataInflasi', [UploadController::class, 'index'])->name('manajemen-data-inflasi.index');
Route::get('/TabelDataInflasi/import', [UploadController::class, 'create'])->name('manajemen-data-inflasi.create');
Route::post('/upload', [UploadController::class, 'store'])->name('manajemen-data-inflasi.store');
Route::get('/TabelDataInflasi/{data_name}', [UploadController::class, 'show'])->name('manajemen-data-inflasi.show');

// DASHBOARD //
Route::get('/dashboard/infkelompok',function(){return view('dashboard.infKelompok');})->name('dashboard.kelompok');
Route::get('/dashboard/infseries',function(){return view('dashboard.infSeries');})->name('dashboard.series');
Route::get('/dashboard/infspasial',function(){return view('dashboard.infSpasial');})->name('dashboard.spasial');
Route::get('/dashboard/infbulanan',function(){return view('dashboard.infBulananJatim');})->name('dashboard.bulanan');



Route::get('/login',function(){
    return view('formLogin');
})->name('login');

// PROV //
Route::get('/adminprov', function () {return view('prov.index');})->name('landingPageProv');
