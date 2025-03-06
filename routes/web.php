<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/main', function () {
    return view('maintenance');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/absen',[AbsenController::class, 'index'])->name('absen');
    Route::get('/absen/create',[AbsenController::class, 'create']);
    Route::post('/absen/store',[AbsenController::class, 'store']);

    Route::get('/tenant', [MasterController::class, 'tenant'])->name('tenant');
    Route::post('/tenant/tambah', [MasterController::class, 'tambahtenant']);

    Route::get('/kantor', [MasterController::class, 'kantor'])->name('kantor');
    Route::post('/kantor/tambah', [MasterController::class, 'tambahkantor']);

    Route::get('/satker', [MasterController::class, 'satker'])->name('satker');
    Route::post('/satker/tambah', [MasterController::class, 'tambahsatker']);
});

Route::post('/loginabsen', [AuthController::class, 'loginabsen']);