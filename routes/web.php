<?php

use App\Http\Controllers\UploadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


// USER //
Route::get('/', function () {return view('user.index');})->name('landingPage');
Route::get('/DaftarTabelInflasi',function(){return view('user.daftar-tabel-inflasi.index');})->name('daftar-tabel-inlfasi.index');

// PROV //
Route::get('/AdminProv', function () {return view('prov.index');})->name('landingPageProv');

Route::get('/check-auth', function() {
    return dd(Auth::user());
});

Route::get('/ManajemenAkun',function(){return view('prov.manajemen-akun.index');})->name('manajemen-akun.index');
Route::get('/MasterSatker',function(){return view('prov.master-satker.index');})->name('master-satker.index');

Route::get('/ManajemenDataInflasi',function(){return view('prov.manajemen-data-inflasi.index');})->name('manajemen-data.index');
Route::get('/MasterKomoditas',function(){return view('prov.master-komoditas.index');})->name('master-komoditas.index');

// KABKOT //
Route::get('/Kabkot', function () {return view('kabkot.index');})->name('landingPageKabkot');

// MANAJEMEN AKUN //
Route::middleware(['auth'])->group(function () {
    Route::get('/ManajemenAkun/import', [UploadController::class, 'create'])->name('manajemen-akun.create');
});

// MANAJEMEN DATA INFLASI //
Route::middleware(['auth'])->group(function () {
    Route::get('/TabelDataInflasi', [UploadController::class, 'index'])->name('manajemen-data-inflasi.index');
    Route::get('/TabelDataInflasi/import', [UploadController::class, 'create'])->name('manajemen-data-inflasi.create');
    Route::post('/upload', [UploadController::class, 'store'])->name('manajemen-data-inflasi.store');
    Route::get('/TabelDataInflasi/{data_name}', [UploadController::class, 'show'])->name('manajemen-data-inflasi.show');
});

// DASHBOARD //
Route::get('/dashboard/infkelompok',function(){return view('dashboard.infKelompok');})->name('dashboard.kelompok');
Route::get('/dashboard/infseries',function(){return view('dashboard.infSeries');})->name('dashboard.series');
Route::get('/dashboard/infspasial',function(){return view('dashboard.infSpasial');})->name('dashboard.spasial');
Route::get('/dashboard/infbulanan',function(){return view('dashboard.infBulananJatim');})->name('dashboard.bulanan');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// COBA //
Route::get('/coba', function () {return view('cobabuat-moda');})->name('inicobabuatmoda');
