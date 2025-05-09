<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PegawaiController;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\RedirectIfPegawaiAuthenticated;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
        Route::delete('/kantor/hapus/{id}', [MasterController::class, 'kantroy']);
    });

    Route::middleware('role:0|1|3')->group(function () {
        Route::get('/get-konten/{companyId}', [MasterController::class, 'getkonten']);
        Route::get('/get-sat/{kantId}', [MasterController::class, 'getsat']);
        Route::get('/get-satker-by-departemen/{deptId}', [MasterController::class, 'getSatkerByDepartemen']);
        Route::get('/get-position-by-satker/{satId}', [MasterController::class, 'getPositionBySatker']);
        Route::get('/get-pegawai/{id}', [MasterController::class, 'bysatker']);
        
        Route::get('/departemen', [MasterController::class, 'dept'])->name('departemen');
        Route::post('/departemen/store', [MasterController::class, 'deptstore'])->name('departemen.store');
        Route::put('/departemen/update/{id}', [MasterController::class, 'deptup']);
        Route::delete('/departemen/{id}', [MasterController::class, 'deptroy'])->name('dept.destroy');


        Route::get('/satker', [MasterController::class, 'satker'])->name('satker');
        Route::post('/satker/tambah', [MasterController::class, 'tambahsatker']);
        Route::put('/satker/edit/{id}', [MasterController::class, 'updatesatker']);
        Route::delete('/satker/hapus/{id}', [MasterController::class, 'destroysatker']);
    
        Route::get('/jabatan', [MasterController::class, 'jabatan'])->name('jabatan');
        Route::post('/jabatan/tambah', [MasterController::class, 'tambahjabatan']);
        Route::delete('/jabatan/hapus/{id}', [MasterController::class, 'destroyjabatan']);
        Route::put('/jabatan/edit/{id}', [MasterController::class, 'updatejabatan']);

        Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::delete('/pegawai/delete/{id}', [PegawaiController::class, 'delete'])->name('pegawai.delete');
        Route::get('/pegawai/detail/{id}', [PegawaiController::class, 'detail'])->name('pegawai.detail');
        Route::get('/pegawai/input', [PegawaiController::class, 'input'])->name('pegawai.input');
        Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('/pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('/pegawai/update/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::post('/pegawai/ubah-password/', [PegawaiController::class, 'ubahpass'])->name('pegawai.upass');
        Route::post('/cek-nip', [PegawaiController::class, 'cekNIP'])->name('cek.nip');
        Route::get('/pegawai/absensi/', [PegawaiController::class, 'absensi'])->name('pegawai.absensi');
        Route::get('/pegawai/absensi/laporan', [PegawaiController::class, 'lapor'])->name('pegawai.absensi.laporan');
        Route::post('/pegawai/absensi/preview', [PegawaiController::class, 'preview'])->name('pegawai.absensi.preview');
        Route::get('/get-abs', [PegawaiController::class, 'getAbs'])->name('get.abs');
        Route::get('/pegawai/absensi/izin', [PegawaiController::class, 'izin'])->name('pegawai.absensi.izin');
        Route::post('/pegawai/absensi/izin/{id}/status', [PegawaiController::class, 'izinstatus']);
        Route::get('/pegawai/absensi/rekap', [PegawaiController::class, 'rekap'])->name('pegawai.absensi.rekap');
        Route::post('/pegawai/absensi/rekapview', [PegawaiController::class, 'rekapview'])->name('pegawai.absensi.rekapview');

        Route::get('/users', [MasterController::class, 'user'])->name('users');
        Route::post('/users/add', [MasterController::class, 'adduser'])->name('adduser');
        Route::put('/users/update', [MasterController::class, 'upuser']);
        Route::delete('/users/delete/{id}', [MasterController::class, 'deluser']);

        Route::get('/shift', [MasterController::class, 'shift'])->name('shift');
        Route::post('/shift/store', [MasterController::class, 'shiftStore'])->name('master.shift.store');
        Route::put('/shift/update/{id}', [MasterController::class, 'shiftUpdate'])->name('master.shift.update');
        Route::delete('/shift/destroy/{id}', [MasterController::class, 'shiftdest'])->name('shift.destroy');


    });
    // web.php
        Route::get('/laporan/{id}', [LaporanController::class, 'perSatker'])->name('laporan.satker');

        Route::get('/laporan/{id}/input', [LaporanController::class, 'input'])->name('lapor.admin.input');
        Route::post('/laporan/{id}/store', [LaporanController::class, 'store'])->name('lapor.admin.store');
        Route::get('/laporan/{id}/detail/{ids}', [LaporanController::class, 'detail'])->name('lapor.admin.detail');
        Route::get('/laporan/{id}/pdf/{ids}', [LaporanController::class, 'savepdf'])->name('lapor.admin.pdf');
        Route::get('/laporan/{id}/edit/{ids}', [LaporanController::class, 'edit'])->name('lapor.admin.edit');
        Route::put('/laporan/{id}/update/{ids}', [LaporanController::class, 'update'])->name('lapor.admin.update');
        Route::post('/laporan/{id}/hapus-foto/{ids}', [LaporanController::class, 'hapusFoto'])->name('lapor.admin.hapusFoto');
        Route::delete('/laporan/{id}/hapus/{ids}', [LaporanController::class, 'destroy'])->name('lapor.admin.destroy');

});


Route::middleware('ifnotpeg')->group(function () {
    Route::get('/absen/login', [AuthController::class, 'showLogin'])->name('absen.login');
});


Route::controller(AbsenController::class)->middleware(['redirif:pegawai'])->group(function () {
    Route::get('/absen', 'index')->name('absen');
    Route::get('/absen/create', 'create')->name('absen.create');
    Route::get('/absen/lembur', 'lembur')->name('absen.lembur');
    Route::post('/absen/lembur/mulai', 'mulaiLembur')->name('absen.lembur.mulai');
    Route::post('/absen/lembur/selesai', 'selesaiLembur')->name('absen.lembur.selesai');
    Route::post('/absen/store', 'store');
    Route::get('/absen/profile', 'profile')->name('absen.profile');
    Route::post('/absen/profile-image', 'profilimage');
    Route::post('/absen/update-nama', 'updateNama')->name('profile.updateNama');
    Route::get('/absen/histori', 'histori')->name('absen.histori');
    Route::post('/absen/gethistori', 'gethistori');
    Route::get('/absen/izin', 'izin')->name('absen.izin');
    Route::get('/absen/formizin', 'formizin')->name('absen.formizin');
    Route::post('/absen/formizinsimpan', 'formizinsimpan')->name('absen.storeizin');
    Route::post('/absen/update-nowa', 'updateNowa');
    Route::post('/absen/update-pass', 'updatePass');
    Route::get('/absen/laporan', 'lapor')->name('absen.lapor');
    Route::get('/absen/laporan/detail/{id}', 'lapordetail')->name('absen.lapor.detail');
    Route::get('/absen/buat_laporan', 'formlap')->name('absen.formlap');
    Route::post('/absen/storelap', 'laporan')->name('absen.storelap');
    Route::get('/absen/savepdf/{id}', 'savepdf')->name('absen.savepdf');
    Route::get('/absen/editlap/{id}', 'editlap')->name('absen.editlap');
    Route::put('/absen/updatelap/{id}', 'updatelap')->name('absen.updatelap');
    Route::delete('/laporan/hapus/{id}', 'destroy')->name('absen.destroy');
    Route::post('/laporan/hapus-foto/{ids}','hapusFoto')->name('absen.hapusFoto');
});

Route::post('/absen/logout', [AuthController::class, 'logout'])->middleware(['redirif:pegawai'])->name('absen.logout');

Route::post('/absen/login', [AuthController::class, 'login'])->middleware('guest');





