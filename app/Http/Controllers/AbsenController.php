<?php

namespace App\Http\Controllers;

use App\Models\AbsenModel;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use carbon\Carbon;

class AbsenController extends Controller
{
    public function index()
    {
        $id = Auth::guard('pegawai')->user()->id; //tabel karyawan
        $nip = Auth::guard('pegawai')->user()->nip;
        $harini = date('Y-m-d');
        $pegawai = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat' )->findOrFail($id);
        $absen = AbsenModel::with('pegawai')->where('tgl_absen', $harini)->where('nip', $id)->first();
        $absens = AbsenModel::where('nip', $id)->where('tgl_absen', 'LIKE', '%'.carbon::now()->format('m').'%')->latest()->get();
        $rekap = AbsenModel::where('nip', $id)
                ->whereMonth('tgl_absen', carbon::now()->format('m'))
                ->whereYear('tgl_absen', carbon::now()->format('Y'))
                ->selectRaw('COUNT(nip) as jmlhadir, SUM(CASE WHEN jam_in > "07:00" THEN 1 ELSE 0 END) as jmltelat')
                ->first();
        $leaderboard = AbsenModel::with('pegawai.perusa', 'pegawai.jabat')
                ->where('tgl_absen', $harini)
                ->where('perusahaan', Auth::guard('pegawai')->user()->perusahaan)
                ->where('kantor', Auth::guard('pegawai')->user()->nama_kantor)
                ->orderBy('jam_in')
                ->get();

        return view('absen.index', compact('pegawai', 'absen', 'absens', 'rekap', 'leaderboard'));
    }

    public function create()
    {
        $harini = date('Y-m-d');
        $nip_id = Auth::guard('pegawai')->user()->id;
        $cek = AbsenModel::where('tgl_absen', $harini)->where('nip', $nip_id)->count();
        $cek2 = AbsenModel::where('tgl_absen', $harini)->where('nip', $nip_id)->first();
        $pegawai = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat' )->findOrFail($nip_id);

        return view('absen.create', compact('pegawai', 'cek', 'cek2'));
    }

    public function store(Request $request)
    {
// dd(storage_path('storage/absensi/'));
        $nip = Auth::guard('pegawai')->user()->nip;
        $nip_id = Auth::guard('pegawai')->user()->id;
        $tgl_absen = date("Y-m-d");
        $jam_absen = date("H:i:s");
        $jam_foto = date("His");
        $lokasi = $request->lokasi;
        $image = $request->image;
        $folderPath = ('storage/absensi/'. $nip .'/');
        $id_perus = Auth::guard('pegawai')->user()->perusahaan;
        $id_kan = Auth::guard('pegawai')->user()->nama_kantor;

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $image_parts = explode(";base64,", $image);
        if (count($image_parts) == 2) {
            $image_base64 = base64_decode($image_parts[1]);
        } else {
            echo "error|Format base64 tidak valid";
            return;
        }

        $cek = AbsenModel::where('tgl_absen', $tgl_absen)->where('nip', $nip_id)->count();
        if ($cek > 0) {
            $formatName = $tgl_absen . "-" . $jam_foto . "-out";
            $fileName = $formatName . ".png";
            $file= $folderPath . $fileName;

            $update = AbsenModel::where('nip', $nip_id)->where('tgl_absen', $tgl_absen)->update([
            'jam_out' => $jam_absen,
            'foto_out' => $fileName,
            'lokasi_out' => $lokasi,
        ]);
            if($update){
                file_put_contents($file, $image_base64);
                echo "success|Terima Kasih, Absen Pulang Berhasil|out";
            } else {
                echo 1;       
            }
        } else {
            $formatName = $tgl_absen . "-" . $jam_foto . "-in";
            $fileName = $formatName . ".png";
            $file= $folderPath . $fileName;

            

            $simpan = AbsenModel::create([
            'nip' => $nip_id,
            'perusahaan' => $id_perus,
            'kantor' => $id_kan,
            'tgl_absen' => $tgl_absen,
            'jam_in' => $jam_absen,
            'foto_in' => $fileName,
            'lokasi_in' => $lokasi,
        ]);
            if($simpan){
                file_put_contents($file, $image_base64);
                echo "success|Terima Kasih, Absen Masuk Berhasil|in";
            } else {
                echo 1;       
            }
        }
    }
}
