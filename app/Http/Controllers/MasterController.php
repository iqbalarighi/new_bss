<?php

namespace App\Http\Controllers;

use App\Models\JabatanModel;
use App\Models\KantorModel;
use App\Models\PerusahaanModel;
use Illuminate\Http\Request;

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
        $perusahaan = PerusahaanModel::get();
        $kantor = KantorModel::paginate(10);

        return view('master.kantor', compact('kantor', 'perusahaan'));
    }

        public function tambahkantor(Request $request)
    {
        // dd($request->kantor);
        $kantor = new KantorModel;

        $kantor->perusahaan = $request->usaha;
        $kantor->nama_kantor = $request->kantor;
        $kantor->alamat = $request->alamat;
        $kantor->radius = $request->radius;
        $kantor->lokasi = $request->lokasi;

        $kantor->save();

        return back()
            ->with('status', 'berhasil');
    }
}
