<?php

use App\Http\Controllers\ManajemenAkunController;
use App\Http\Controllers\MasterSatkerController;
use App\Http\Controllers\MasterKomoditasController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


// USER //
Route::get('/', function () {
    return view('user.index');
})->name('landingPage');
Route::get('/DaftarTabelInflasi', function () {
    return view('user.daftar-tabel-inflasi.index');
})->name('daftar-tabel-inlfasi.index');

// PROV //
Route::get('/AdminProv', function () {
    return view('prov.index');
})->name('landingPageProv');

Route::get('/check-auth', function () {
    return dd(Auth::user());
});

Route::get('/ManajemenDataInflasi', function () {
    return view('prov.manajemen-data-inflasi.index');
})->name('manajemen-data.index');
Route::get('/MasterKomoditas', function () {
    return view('prov.master-komoditas.index');
})->name('master-komoditas.index');

// KABKOT //
Route::get('/Kabkot', function () {
    return view('kabkot.index');
})->name('landingPageKabkot');

// MANAJEMEN AKUN //
Route::middleware(['auth'])->group(function () {
    Route::get('/ManajemenAkun', [ManajemenAkunController::class, 'index'])->name('manajemen-akun.index');
    Route::get('/ManajemenAkun/tambah', [ManajemenAkunController::class, 'create'])->name('manajemen-akun.create');
    Route::post('manajemen-akun', [ManajemenAkunController::class, 'store'])->name('manajemen-akun.store');
    Route::get('/ManajemenAkun/{id}/edit', [ManajemenAkunController::class, 'edit'])->name('manajemen-akun.edit');
    Route::put('manajemen-akun/{id}', [ManajemenAkunController::class, 'update'])->name('manajemen-akun.update');
    Route::delete('manajemen-akun/{id}', [ManajemenAkunController::class, 'destroy'])->name('manajemen-akun.destroy');
});

// MASTER SATKER //
Route::middleware(['auth'])->group(function () {
    Route::get('/MasterSatker', [MasterSatkerController::class, 'index'])->name('master-satker.index');
    Route::post('/MasterSatker', [MasterSatkerController::class, 'store'])->name('master-satker.store');
    Route::delete('/MasterSatker/{kode_satker}', [MasterSatkerController::class, 'destroy'])->name('master-satker.destroy');
    Route::get('/MasterSatker/{kode_satker}/edit', [MasterSatkerController::class, 'edit'])->name('master-satker.edit');
    Route::put('/MasterSatker/{kode_satker}', [MasterSatkerController::class, 'update'])->name('master-satker.update');
});

// MANAJEMEN DATA INFLASI //
Route::middleware(['auth'])->group(function () {
    Route::get('/TabelDataInflasi', [UploadController::class, 'index'])->name('manajemen-data-inflasi.index');
    Route::get('/TabelDataInflasi/import', [UploadController::class, 'create'])->name('manajemen-data-inflasi.create');
    Route::post('/upload', [UploadController::class, 'store'])->name('manajemen-data-inflasi.store');
    Route::get('/TabelDataInflasi/{data_name}', [UploadController::class, 'show'])->name('manajemen-data-inflasi.show');
    Route::delete('/TabelDataInflasi/{id}', [UploadController::class, 'destroy'])->name('manajemen-data-inflasi.destroy');
    Route::get('/TabelDataInflasi/{id}/edit', [UploadController::class, 'edit'])->name('manajemen-data-inflasi.edit');
    Route::put('/TabelDataInflasi/{id}', [UploadController::class, 'update'])->name('manajemen-data-inflasi.update');
});

// MASTER KOMODITAS //
Route::middleware(['auth'])->group(function () {
    Route::get('/MasterKomoditas', [MasterKomoditasController::class, 'index'])->name('master-komoditas.index');
    Route::post('/MasterKomoditas', [MasterKomoditasController::class, 'store'])->name('master-komoditas.store');
    Route::delete('/MasterKomoditas/{kode_kom}', [MasterKomoditasController::class, 'destroy'])->name('master-komoditas.destroy');
    Route::get('/MasterKomoditas/create', [MasterKomoditasController::class, 'create'])->name('master-komoditas.create');
    Route::post('/MasterKomoditas/store', [MasterKomoditasController::class, 'store'])->name('master-komoditas.store');
    Route::get('/MasterKomoditas/{kode_kom}/edit', [MasterKomoditasController::class, 'edit'])->name('master-komoditas.edit');
    Route::put('/MasterKomoditas/{kode_kom}', [MasterKomoditasController::class, 'update'])->name('master-komoditas.update');
});

// DASHBOARD //
Route::get('/dashboard/infkelompok', function () {
    return view('dashboard.infKelompok');
})->name('dashboard.kelompok');
Route::get('/dashboard/infseries', function () {
    return view('dashboard.infSeries');
})->name('dashboard.series');
Route::get('/dashboard/infspasial', function () {
    return view('dashboard.infSpasial');
})->name('dashboard.spasial');
Route::get('/dashboard/infbulanan', [DashboardController::class, 'showInflasiBulanan'])->name('dashboard.bulanan');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/upload-inflasi', [UploadController::class, 'uploadInflasiAjax'])->name('upload.inflasi.ajax');

// COBA //
Route::get('/coba', function () {
    return view('cobabuat-moda');
})->name('inicobabuatmoda');
