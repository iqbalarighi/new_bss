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
        $kantor = KantorModel::paginate(10);
        $jabatan = JabatanModel::paginate(10);

        return view('master.tenant', compact('perusahaan', 'kantor', 'jabatan'));
    }

    public function tambah(Request $request)
    {

        $tambah = new PerusahaanModel;

        $tambah->perusahaan = $request->tenant;
        $tambah->alamat = $request->alamat;
        $tambah->no_tlp = $request->telp;

        $tambah->save();

        return redirect()->back()
        ->with('success', 'Tambah Data Berhasil');
    }
}
