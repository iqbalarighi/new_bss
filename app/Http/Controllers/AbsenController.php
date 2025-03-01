<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsenController extends Controller
{
    public function index()
    {
        return view('absen.index');
    }

    public function create()
    {
        return view('absen.create');
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

        Storage::disk('local')->put($file, $image_base64);


        return view('absen.create');
    }
}
