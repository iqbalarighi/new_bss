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
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
        if(Auth::guard('web')->check()){
            return redirect('home');
        }
        else {
            return view('auth.login');
        }
    });

Route::get('/main', function () {
    return view('maintenance');
});

Auth::routes([
    'register' => false,
    // 'login' => false
]);


Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/tenant', [MasterController::class, 'tenant'])->name('tenant')->middleware('role:0');
    Route::post('/tenant/tambah', [MasterController::class, 'tambahtenant'])->middleware('role:0');
    Route::put('/tenant/edit/{id}', [MasterController::class, 'edittenant'])->middleware('role:0');
    Route::delete('/tenant/hapus/{id}', [MasterController::class, 'destroytenant'])->middleware('role:0');

    // if(Auth::guard())
    Route::get('/kantor', [MasterController::class, 'kantor'])->name('kantor')->middleware('role:0|1');
    Route::post('/kantor/tambah', [MasterController::class, 'tambahkantor'])->middleware('role:0|1');
    Route::get('/kantor/edit/{id}', [MasterController::class, 'kantoredit'])->middleware('role:0|1');
    Route::put('/kantor/edit/{id}', [MasterController::class, 'kantorupdate'])->middleware('role:0|1');

    Route::get('/get-konten/{companyId}', [MasterController::class, 'getkonten']);

    Route::get('/satker', [MasterController::class, 'satker'])->name('satker');
    Route::post('/satker/tambah', [MasterController::class, 'tambahsatker']);
    Route::put('/satker/edit/{id}', [MasterController::class, 'updatesatker']);
 
    Route::get('/jabatan', [MasterController::class, 'jabatan'])->name('jabatan');
    Route::post('/jabatan/tambah', [MasterController::class, 'tambahjabatan']);

    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/input', [PegawaiController::class, 'input'])->name('pegawai.input');
    Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');

    Route::get('/users', [MasterController::class, 'user'])->name('users');
    Route::post('/users/add', [MasterController::class, 'adduser'])->name('adduser');
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





