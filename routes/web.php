<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/main', function () {
    return view('maintenance');
});

Auth::routes();

Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [HomeController::class, 'index']);

    Route::get('/tenant', [MasterController::class, 'tenant'])->name('tenant');
    Route::post('/tenant/tambah', [MasterController::class, 'tambahtenant']);

    Route::get('/kantor', [MasterController::class, 'kantor'])->name('kantor');
    Route::post('/kantor/tambah', [MasterController::class, 'tambahkantor']);

    Route::get('/satker', [MasterController::class, 'satker'])->name('satker');
    Route::post('/satker/tambah', [MasterController::class, 'tambahsatker']);
 
    Route::get('/jabatan', [MasterController::class, 'jabatan'])->name('jabatan');
    Route::post('/jabatan/tambah', [MasterController::class, 'tambahjabatan']);
});

Route::post('/loginabsen', [AuthController::class, 'loginabsen']);

Route::middleware(['auth:karyawan'])->group(function () {
Route::get('/absen',[AbsenController::class, 'index'])->name('absen');
    Route::get('/absen/create',[AbsenController::class, 'create']);
    Route::post('/absen/store',[AbsenController::class, 'store']);
});