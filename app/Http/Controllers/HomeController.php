<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AbsenModel;
use App\Models\IzinabsenModel;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $harini = Carbon::now()->format('Y-m-d');
        $bulini = Carbon::now()->format('Y-m');
//buat per akun kantor pakai Auth

    if(Auth::user()->role == 0) {
        $rekap = AbsenModel::whereMonth('tgl_absen', carbon::now()->format('m'))
                ->where('tgl_absen', $harini)
                ->selectRaw('COUNT(nip) as jmlhadir')
                ->first();

        $rekapizin = IzinabsenModel::where('created_at', 'LIKE', '%'.$bulini.'%')
                    ->selectRaw("
                        SUM(CASE WHEN jenis_izin = 'i' THEN 1 ELSE 0 END) as izin, 
                        SUM(CASE WHEN jenis_izin = 's' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN jenis_izin = 'c' THEN 1 ELSE 0 END) as cuti
                    ")
                    ->where('status_approve', 1)
                    ->first();
    }
        if(Auth::user()->role == 1) {
            $rekap = AbsenModel::whereMonth('tgl_absen', carbon::now()->format('m'))
                ->where('perusahaan', Auth::user()->perusahaan)
                ->where('tgl_absen', $harini)
                ->selectRaw('COUNT(nip) as jmlhadir')
                ->first();
                
        $rekapizin = IzinabsenModel::where('created_at', 'LIKE', '%'.$bulini.'%')
                    ->where('perusahaan', Auth::user()->perusahaan)
                    ->selectRaw("
                        SUM(CASE WHEN jenis_izin = 'i' THEN 1 ELSE 0 END) as izin, 
                        SUM(CASE WHEN jenis_izin = 's' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN jenis_izin = 'c' THEN 1 ELSE 0 END) as cuti
                    ")
                    ->where('status_approve', 1)
                    ->first();
        } 

        if(Auth::user()->role == 3) {
            $rekap = AbsenModel::whereMonth('tgl_absen', carbon::now()->format('m'))
                ->where('perusahaan', Auth::user()->perusahaan)
                ->where('kantor', Auth::user()->kantor)
                ->where('tgl_absen', $harini)
                ->selectRaw('COUNT(nip) as jmlhadir')
                ->first();

        $rekapizin = IzinabsenModel::where('created_at', 'LIKE', '%'.$bulini.'%')
                    ->where('perusahaan', Auth::user()->perusahaan)
                    ->where('nama_kantor', Auth::user()->kantor)
                    ->selectRaw("
                        SUM(CASE WHEN jenis_izin = 'i' THEN 1 ELSE 0 END) as izin, 
                        SUM(CASE WHEN jenis_izin = 's' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN jenis_izin = 'c' THEN 1 ELSE 0 END) as cuti
                    ")
                    ->where('status_approve', 1)
                    ->first();
        } 


        return view('home', compact('rekap', 'rekapizin'));
    }
}
