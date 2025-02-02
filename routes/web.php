<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.InfBulananJatim');
});

Route::get('/input', function () {
    return view('input.main');
});
