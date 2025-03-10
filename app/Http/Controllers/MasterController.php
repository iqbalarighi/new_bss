<?php

namespace App\Http\Controllers;

use App\Models\JabatanModel;
use App\Models\KantorModel;
use App\Models\PerusahaanModel;
use App\Models\SatkerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{
    public function tenant()
    {
        $perusahaan = PerusahaanModel::paginate(10);
        
        $jabatan = JabatanModel::paginate(10);

        return view('master.tenant', compact('perusahaan'));
    }

    public function tambahtenant(Request $request)
    {

        $tambah = new PerusahaanModel;

        $tambah->perusahaan = $request->tenant;
        $tambah->alamat = $request->alamat;
        $tambah->no_tlp = $request->telp;

        $tambah->save();

        return back()
            ->with('status', 'berhasil');
    }


    public function kantor()
    {
        if(Auth::user()->role === 0){
            $perusahaan = PerusahaanModel::get();
            $kantor = KantorModel::with('perusa')->paginate(15);

            return view('master.kantor', compact('kantor', 'perusahaan'));
        } else {
            $kantor = KantorModel::with('perusa')
            ->where('perusahaan', Auth::user()->perusahaan)
            ->paginate(15);

            return view('master.kantor', compact('kantor'));
        }
    }

        public function tambahkantor(Request $request)
    {
        if(Auth::user()->role === 0){
            $perusa = $request->usaha;
        } else {
            $perusa = Auth::user()->perusahaan;
        }

        $kantor = new KantorModel;

        $kantor->perusahaan = $perusa;
        $kantor->nama_kantor = $request->kantor;
        $kantor->alamat = $request->alamat;
        $kantor->radius = $request->radius;
        $kantor->lokasi = $request->lokasi;

        $kantor->save();

        return back()
            ->with('status', 'berhasil');
    }
    
    public function satker()
    {
        if(Auth::user()->role === 0){
            $perusahaan = PerusahaanModel::get();
            $satker = SatkerModel::paginate(15);

        return view('master.satker', compact('satker', 'perusahaan'));
        } else {
           $satker = SatkerModel::paginate(15);

        return view('master.satker', compact('satker'));
        }

        
    }

    public function tambahsatker(Request $request)
    {

        if(Auth::user()->role === 0){
            $perusa = $request->perusahaan;
        } else {
            $perusa = Auth::user()->perusahaan;
        }

        $satker = new SatkerModel;

        $satker->perusahaan = $perusa;
        $satker->satuan_kerja = $request->satker;

        $satker->save();

        return back()
        ->with('status', 'berhasil');
    }

    public function jabatan()
    {
        if(Auth::user()->role === 0){
            $perusahaan = PerusahaanModel::get();
            $jabatan = JabatanModel::paginate(15);

        return view('master.jabatan', compact('jabatan', 'perusahaan'));
        } else {
           $jabatan = JabatanModel::where('perusahaan', Auth::user()->perusahaan)
           ->paginate(15);

        return view('master.jabatan', compact('jabatan'));
        }

    }

    public function tambahjabatan(Request $request)
    {
        if(Auth::user()->role === 0){
            $perusa = $request->usaha;
        } else {
            $perusa = Auth::user()->perusahaan;
        }

        $jabatan = new JabatanModel;

        $jabatan->perusahaan = $perusa;
        $jabatan->jabatan = $request->jabatan;
         $jabatan->save();

        return back()
        ->with('status', 'berhasil');
    }
}
