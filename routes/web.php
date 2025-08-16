<?php

use App\Http\Controllers\ManajemenAkunController;
use App\Http\Controllers\MasterSatkerController;
use App\Http\Controllers\MasterKomoditasController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DaftarTabelInflasiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MasterKomUtamaController;


// USER //
Route::get('/', [UploadController::class, 'landing'])->name('landingPage');

Route::get('/DaftarTabelInflasi', [DaftarTabelInflasiController::class, 'index'])->name('daftar-tabel-inflasi.index');
Route::get('/DaftarTabelInflasi/{id}', [DaftarTabelInflasiController::class, 'show'])->name('daftar-tabel-inflasi.show');
Route::get('/DaftarTabelInflasi/{id}/download', [DaftarTabelInflasiController::class, 'download'])->name('daftar-tabel-inflasi.download');


Route::get('/check-auth', function () {
    return dd(Auth::user());
});


Route::get('/Kabkot', function () {
    return view('kabkot.index');
})->name('kabkot.index');
Route::get('/Prov', function () {
    return view('prov.index');
})->name('prov.index');
Route::get('/AdminProv', function () {
    return view('prov.index');
})->name('landingPageProv');


// DASHBOARD //
Route::get('/dashboard/infkelompok', [DashboardController::class, 'showInflasiKelompok'])->name('dashboard.kelompok');
Route::get('/dashboard/infseries', [DashboardController::class, 'showSeriesInflasi'])->name('dashboard.series');
Route::get('/dashboard/infspasial', [DashboardController::class, 'showInflasiSpasial'])->name('dashboard.spasial');
Route::get('/dashboard/export-excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');
Route::post('/dashboard/export-tabel-dinamis', [\App\Http\Controllers\DashboardController::class, 'exportTabelDinamis'])->name('dashboard.exportTabelDinamis');
Route::get('/dashboard/spasial/komoditas-kabkota-data', [DashboardController::class, 'getInflasiKomoditasKabKotaAjax']);
Route::post('/dashboard/spasial/tabel-dinamis-data', [DashboardController::class, 'tabelDinamisData'])->name('dashboard.spasial.tabel-dinamis-data');

// ROUTE MANAJEMEN DATA INFLASI YANG BISA DIAKSES SEMUA YANG LOGIN
Route::middleware(['auth'])->group(function () {
    Route::get('/TabelDataInflasi', [UploadController::class, 'index'])->name('manajemen-data-inflasi.index');
    // Route yang lebih spesifik harus diletakkan sebelum route dengan parameter
    Route::get('/TabelDataInflasi/import', [UploadController::class, 'create'])->name('manajemen-data-inflasi.create');
    Route::get('/TabelDataInflasi/{data_name}', [UploadController::class, 'show'])->name('manajemen-data-inflasi.show');
});

    // MANAGEMEN AKUN //
    Route::get('/ManajemenAkun', [ManajemenAkunController::class, 'index'])->name('manajemen-akun.index');
    Route::get('/ManajemenAkun/tambah', [ManajemenAkunController::class, 'create'])->name('manajemen-akun.create');
    Route::post('manajemen-akun', [ManajemenAkunController::class, 'store'])->name('manajemen-akun.store');
    Route::get('/ManajemenAkun/{id}/edit', [ManajemenAkunController::class, 'edit'])->name('manajemen-akun.edit');
    Route::put('manajemen-akun/{id}', [ManajemenAkunController::class, 'update'])->name('manajemen-akun.update');
    Route::delete('manajemen-akun/{id}', [ManajemenAkunController::class, 'destroy'])->name('manajemen-akun.destroy');

    // MASTER SATKER //
    Route::get('/MasterSatker', [MasterSatkerController::class, 'index'])->name('master-satker.index');
    Route::post('/MasterSatker', [MasterSatkerController::class, 'store'])->name('master-satker.store');
    Route::delete('/MasterSatker/{kode_satker}', [MasterSatkerController::class, 'destroy'])->name('master-satker.destroy');
    Route::get('/MasterSatker/{kode_satker}/edit', [MasterSatkerController::class, 'edit'])->name('master-satker.edit');
    Route::put('/MasterSatker/{kode_satker}', [MasterSatkerController::class, 'update'])->name('master-satker.update');

    // MANAJEMEN DATA INFLASI (KHUSUS PROVINSI)
    Route::post('/upload-inflasi', [UploadController::class, 'uploadInflasiAjax'])->name('upload.inflasi.ajax');
    Route::post('/update-inflasi/{id}', [UploadController::class, 'updateInflasiAjax'])->name('update.inflasi.ajax');
    Route::delete('/TabelDataInflasi/{id}', [UploadController::class, 'destroy'])->name('manajemen-data-inflasi.destroy');
    Route::get('/TabelDataInflasi/{id}/edit', [UploadController::class, 'edit'])->name('manajemen-data-inflasi.edit');
    Route::put('/TabelDataInflasi/{id}', [UploadController::class, 'update'])->name('manajemen-data-inflasi.update');


    // MASTER KOMODITAS //
    Route::get('/MasterKomoditas', [MasterKomoditasController::class, 'index'])->name('master-komoditas.index');
    Route::post('/MasterKomoditas', [MasterKomoditasController::class, 'store'])->name('master-komoditas.store');
    Route::delete('/MasterKomoditas/{kode_kom}', [MasterKomoditasController::class, 'destroy'])->name('master-komoditas.destroy');
    Route::get('/MasterKomoditas/create', [MasterKomoditasController::class, 'create'])->name('master-komoditas.create');
    Route::post('/MasterKomoditas/store', [MasterKomoditasController::class, 'store'])->name('master-komoditas.store');
    Route::get('/MasterKomoditas/{kode_kom}/edit', [MasterKomoditasController::class, 'edit'])->name('master-komoditas.edit');
    Route::put('/MasterKomoditas/{kode_kom}', [MasterKomoditasController::class, 'update'])->name('master-komoditas.update');

    // MASTER KOMODITAS UTAMA
    Route::get('/KomoditasUtama', [MasterKomoditasController::class, 'indexKomUtama'])->name('komoditas-utama.index');
    Route::post('/KomoditasUtama', [MasterKomoditasController::class, 'storeKomUtama'])->name('komoditas-utama.storeKomUtama');
    Route::get('/KomoditasUtama/search', [MasterKomoditasController::class, 'searchKomUtama'])->name('komoditas-utama.searchKomUtama');
    Route::get('/KomoditasUtama/{kode_kom}/edit', [MasterKomoditasController::class, 'editKomUtama'])->name('komoditas-utama.editKomUtama');
    Route::put('/KomoditasUtama/{kode_kom}', [MasterKomoditasController::class, 'updateKomUtama'])->name('komoditas-utama.updateKomUtama');
    Route::delete('/KomoditasUtama/{kode_kom}', [MasterKomoditasController::class, 'destroyKomUtama'])->name('komoditas-utama.destroyKomUtama');

// ROUTES UNTUK ADMIN PROVINSI //
Route::middleware(['auth', 'provinsi'])->group(function () {
    
});

// ROUTES UNTUK ADMIN KABKOT //
Route::middleware(['auth', 'kabkot'])->group(function () {

    // Route lain untuk Admin Kabkot bisa ditambahkan di sini
});

// AUTH ROUTES //
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
