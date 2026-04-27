<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SatkerModel;
use App\Models\LaporanModel;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.*', function ($view) {
            if (!Auth::check()) return;

            $user = Auth::user();

            // Ambil ID satker dari laporan yang tersedia berdasarkan role
            $laporanQuery = LaporanModel::query();

            if($user->role == 0){
               
            } elseif($user->role == 1){
                $laporanQuery->where('perusahaan', $user->perusahaan);
            } elseif($user->role == 3){
                $laporanQuery->where('perusahaan', $user->perusahaan)->where('kantor', $user->kantor);
            }

            $satkerIds = $laporanQuery->pluck('satker')->unique(); // Asumsikan kolom satker_id ada di LaporanModel

            $satkers = SatkerModel::whereIn('id', $satkerIds)->get();

            $view->with('satkers', $satkers);
        });
    }

    public function register(): void
    {
        //
    }
}
