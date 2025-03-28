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
//buat per akun kantor pakai Auth

        $rekap = AbsenModel::whereMonth('tgl_absen', carbon::now()->format('m'))
                ->where('tgl_absen', $harini)
                ->selectRaw('COUNT(nip) as jmlhadir, SUM(CASE WHEN jam_in > "07:00" THEN 1 ELSE 0 END) as jmltelat')
                ->first();


        $rekapizin = IzinabsenModel::where('tanggal', $harini)
                    ->selectRaw("
                        SUM(CASE WHEN jenis_izin = 'i' THEN 1 ELSE 0 END) as izin, 
                        SUM(CASE WHEN jenis_izin = 's' THEN 1 ELSE 0 END) as sakit
                    ")
                    ->where('status_approve', 1)
                    ->first();

        return view('home', compact('rekap', 'rekapizin'));
    }
}
