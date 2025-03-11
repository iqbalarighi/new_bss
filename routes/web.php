<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PegawaiController;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\RedirectIfPegawaiAuthenticated;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;





Route::get('/', function () {
    return view('auth.login');
});
Route::get('/main', function () {
    return view('maintenance');
});

Auth::routes([
    'register' => false,
    // 'login' => false
]);


Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [HomeController::class, 'index']);

    Route::get('/tenant', [MasterController::class, 'tenant'])->name('tenant')->middleware(RoleMiddleware::class.':0');
        Route::post('/tenant/tambah', [MasterController::class, 'tambahtenant']);
    

    Route::get('/kantor', [MasterController::class, 'kantor'])->name('kantor');
    Route::post('/kantor/tambah', [MasterController::class, 'tambahkantor']);

    Route::get('/satker', [MasterController::class, 'satker'])->name('satker');
    Route::post('/satker/tambah', [MasterController::class, 'tambahsatker']);
 
    Route::get('/jabatan', [MasterController::class, 'jabatan'])->name('jabatan');
    Route::post('/jabatan/tambah', [MasterController::class, 'tambahjabatan']);

    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/input', [PegawaiController::class, 'input'])->name('pegawai.input');
    Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
});


Route::middleware('ifnotpeg')->group(function () {
    Route::get('/pegawai/login', [AuthController::class, 'showLogin'])->name('pegawai.login');
});


Route::middleware(['redirif:pegawai'])->group(function () {
    Route::get('/absen',[AbsenController::class, 'index'])->name('absen');
    Route::get('/absen/create',[AbsenController::class, 'create']);
    Route::post('/absen/store',[AbsenController::class, 'store']);
    Route::post('/pegawai/logout', [AuthController::class, 'logout'])->name('pegawai.logout');
});


Route::post('/pegawai/login', [AuthController::class, 'login'])->middleware('guest');





