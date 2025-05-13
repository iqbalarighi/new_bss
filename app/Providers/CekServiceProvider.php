<?php

namespace App\Providers;

use App\Models\AbsenModel;
use App\Models\LemburModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class CekServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
       View::composer('layouts.absen.bottomnav', function ($view) {
        $user = Auth::guard('pegawai')->user();

        $absenData = [
            'count' => 0,
            'data' => null,
        ];

        if ($user) {
            $id = $user->id;
            $harini = date('Y-m-d');

            $cek = AbsenModel::where('tgl_absen', $harini)
                ->where('nip', $id)
                ->count();

            $cek2 = AbsenModel::where('tgl_absen', $harini)
                ->where('nip', $id)
                ->first();

            $lembr = LemburModel::where('nip', $id)
            ->where('tgl_absen', $harini)
            ->first();

            $ceklem = LemburModel::where('nip', $id)
                ->where('tgl_absen', '<', $harini)
                ->whereNull('jam_out')
                ->orderByDesc('tgl_absen')
                ->first();

            if($cek2 == null){
                $absenTerakhir = AbsenModel::where('nip', $id)
                    ->where('tgl_absen', '<', $harini)
                    ->orderByDesc('created_at')
                    ->first();
                } else {
                    $absenTerakhir = null;
                }

            }

        $view->with(compact('cek', 'cek2', 'lembr', 'ceklem', 'absenTerakhir'));
    });

    }
}
