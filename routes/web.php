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

    Route::middleware('role:0')->group(function () {
        Route::get('/tenant', [MasterController::class, 'tenant'])->name('tenant');
        Route::post('/tenant/tambah', [MasterController::class, 'tambahtenant']);
        Route::put('/tenant/edit/{id}', [MasterController::class, 'edittenant']);
        Route::delete('/tenant/hapus/{id}', [MasterController::class, 'destroytenant']);
    });

    // if(Auth::guard())
    Route::middleware('role:0|1')->group(function () {
        Route::get('/kantor', [MasterController::class, 'kantor'])->name('kantor');
        Route::post('/kantor/tambah', [MasterController::class, 'tambahkantor']);
        Route::get('/kantor/edit/{id}', [MasterController::class, 'kantoredit']);
        Route::put('/kantor/edit/{id}', [MasterController::class, 'kantorupdate']);
        Route::delete('/kantor/hapus/{id}', [MasterController::class, 'kantorhapus']);
    });

    Route::middleware('role:0|1|3')->group(function () {
        Route::get('/get-konten/{companyId}', [MasterController::class, 'getkonten']);
        
        Route::get('/satker', [MasterController::class, 'satker'])->name('satker');
        Route::post('/satker/tambah', [MasterController::class, 'tambahsatker']);
        Route::put('/satker/edit/{id}', [MasterController::class, 'updatesatker']);
        Route::delete('/satker/hapus/{id}', [MasterController::class, 'destroysatker']);
    
        Route::get('/jabatan', [MasterController::class, 'jabatan'])->name('jabatan');
        Route::post('/jabatan/tambah', [MasterController::class, 'tambahjabatan']);
        Route::delete('/jabatan/hapus/{id}', [MasterController::class, 'destroyjabatan']);
        Route::put('/jabatan/edit/{id}', [MasterController::class, 'updatejabatan']);

        Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/detail/{id}', [PegawaiController::class, 'detail'])->name('pegawai.detail');
        Route::get('/pegawai/input', [PegawaiController::class, 'input'])->name('pegawai.input');
        Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::post('/pegawai/ubah-password/', [PegawaiController::class, 'ubahpass'])->name('pegawai.upass');
        Route::post('/cek-nip', [PegawaiController::class, 'cekNIP'])->name('cek.nip');

        Route::get('/users', [MasterController::class, 'user'])->name('users');
        Route::post('/users/add', [MasterController::class, 'adduser'])->name('adduser');

    });
});


Route::middleware('ifnotpeg')->group(function () {
    Route::get('/absen/login', [AuthController::class, 'showLogin'])->name('absen.login');
});


Route::middleware(['redirif:pegawai'])->group(function () {
    Route::get('/absen',[AbsenController::class, 'index'])->name('absen');
    Route::get('/absen/create',[AbsenController::class, 'create']);
    Route::post('/absen/store',[AbsenController::class, 'store']);
    Route::post('/absen/logout', [AuthController::class, 'logout'])->name('absen.logout');
});


Route::post('/absen/login', [AuthController::class, 'login'])->middleware('guest');





