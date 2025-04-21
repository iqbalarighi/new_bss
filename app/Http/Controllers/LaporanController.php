<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LaporanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;

class LaporanController extends Controller
{
    public function index()
    {
        $lapor = LaporanModel::latest()->paginate(10);

        return view('laporan.admin.index', compact('lapor'));
    }

    public function input()
    {
        return view('laporan.admin.input');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'personil' => 'required',
            'kegiatan' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:4096', // Validasi file gambar
        ]);

        // Generate no_lap

        // Handle upload foto
        if ($request->hasFile('foto')) {
        // Buat direktori penyimpanan di dalam folder public
        $noLap = LaporanModel::generateNoLap(); // pastikan variabel $noLap tersedia
        $directory = public_path('storage/laporan/admin/' . $noLap);
        // $directory = base_path('../public_html/storage/admin/' . $noLap);

        // Buat direktori jika belum ada
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0777, true);
        }

        // Buat nama file acak
        $extension = $request->file('foto')->getClientOriginalExtension();
        $fotoName = Str::random(20) . '.' . $extension;

        // Kompres dan simpan foto menggunakan Intervention Image v3
        $manager = new ImageManager(new Driver());  // default pakai 'gd'
        $image = $manager->read($request->file('foto')->getPathname());

        // Resize dengan aspect ratio dan upsize
        $image->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Simpan dengan kualitas 75%
        $image->toJpeg(75)->save($directory . '/' . $fotoName);
    } else {
        $fotoName = null;
    }

        // Simpan data ke database
        LaporanModel::create([
            'perusahaan' => Auth::user()->perusahaan,
            'kantor' => Auth::user()->kantor,
            'dept' => Auth::user()->dept,
            'satker' => Auth::user()->satker,
            'jabatan' => Auth::user()->jabatan,
            'user_id' => Auth::user()->id,
            'no_lap' => LaporanModel::generateNoLap(),
            'personil' => $request->personil,
            'kegiatan' => $request->kegiatan,
            'keterangan' => $request->keterangan,
            'foto' => $fotoName,
        ]);

        // Response JSON untuk AJAX
        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil disimpan!',
        ]);
    }
}
