<?php

namespace App\Http\Controllers;

use App\Models\AbsenModel;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsenController extends Controller
{
    public function index()
    {
        $id = Auth::guard('pegawai')->user()->id;
        $nip = Auth::guard('pegawai')->user()->nip;
        $harini = date('Y-m-d');
        $pegawai = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat' )->findOrFail($id);
        $absen = AbsenModel::where('tgl_absen', $harini)->where('nip', $nip)->first();

        return view('absen.index', compact('pegawai', 'absen'));
    }

    public function create()
    {
        $harini = date('Y-m-d');
        $nip = Auth::guard('pegawai')->user()->nip;
        $cek = AbsenModel::where('tgl_absen', $harini)->where('nip', $nip)->count();
        $cek2 = AbsenModel::where('tgl_absen', $harini)->where('nip', $nip)->first();
        $id = Auth::guard('pegawai')->user()->id;
        $pegawai = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat' )->findOrFail($id);

        return view('absen.create', compact('pegawai', 'cek', 'cek2'));
    }

    public function store(Request $request)
    {
// dd(storage_path('storage/absensi/'));
        $nip = Auth::guard('pegawai')->user()->nip;
        $tgl_absen = date("Y-m-d");
        $jam_absen = date("H:i:s");
        $jam_foto = date("His");
        $lokasi = $request->lokasi;
        $image = $request->image;
        $folderPath = ('uploads/absensi/'. $nip .'/');
        $formatName = $tgl_absen . "-" . $jam_foto;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file= $folderPath . $fileName;

        $cek = AbsenModel::where('tgl_absen', $tgl_absen)->where('nip', $nip)->count();
        if ($cek > 0) {

            $update = AbsenModel::where('nip', $nip)->where('tgl_absen', $tgl_absen)->update([
            'jam_out' => $jam_absen,
            'foto_out' => $fileName,
            'lokasi_out' => $lokasi,
        ]);
            if($update){
                $stor = Storage::put($file, $image_base64);
                echo "success|Terima Kasih, Absen Pulang Berhasil|out";
            } else {
                echo 1;       
            }
        } else {

            $simpan = AbsenModel::create([
            'nip' => $nip,
            'tgl_absen' => $tgl_absen,
            'jam_in' => $jam_absen,
            'foto_in' => $fileName,
            'lokasi_in' => $lokasi,
        ]);
            if($simpan){
                $stor = Storage::put($file, $image_base64);
                echo "success|Terima Kasih, Absen Masuk Berhasil|in";
            } else {
                echo 1;       
            }
        }
    }
}
