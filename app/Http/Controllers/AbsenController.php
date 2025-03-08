<?php

namespace App\Http\Controllers;

use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsenController extends Controller
{
    public function index()
    {
        $id = Auth::guard('pegawai')->user()->id;

        $pegawai = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat' )->findOrFail($id);


        return view('absen.index', compact('pegawai'));
    }

    public function create()
    {
        $id = Auth::guard('pegawai')->user()->id;

        $pegawai = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat' )->findOrFail($id);
        
        return view('absen.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
// dd(storage_path('storage/absensi/'));
        $nip = '12347';
        $tgl_absen = date("Y-m-d");
        $jam_absen = date("His");
        $lokasi = $request->lokasi;
        $image = $request->image;
        $folderPath = ('uploads/absensi/'. $nip);
        $formatName = $tgl_absen . "-" . $jam_absen;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file= $folderPath . $fileName;

        $stor = Storage::put($file, $image_base64);

    if ($stor) {
        echo 0;
    } else {
        echo 1;
    }

    }
}
