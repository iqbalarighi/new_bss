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
        // Buat direktori penyimpanan
        $noLap = LaporanModel::generateNoLap();
        $directory = public_path('storage/laporan/admin/' . $noLap);

        // Buat folder jika belum ada
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0777, true);
        }

        $files = $request->file('foto');
        $fotoNames = [];

        $manager = new ImageManager(new Driver()); // Inisialisasi di luar loop

        foreach ($files as $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $fotoName = Str::random(20) . '.' . $extension;

            $image = $manager->read($file->getPathname());

            // Resize (aspect ratio & upsize)
            $image->scale(width: 800, keepAspectRatio: true);

            // Simpan sebagai JPEG dengan kualitas 75%
            $image->toJpeg(75)->save($directory . '/' . $fotoName);

            $fotoNames[] = $fotoName;
        }

        $foto = implode('|', $fotoNames);
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
            'foto' => $foto,
        ]);

        // Response JSON untuk AJAX
        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil disimpan!',
        ]);
    }

    public function detail($id)
    {
        $detail = LaporanModel::findOrFail($id);

       return view('laporan.admin.detail', compact('detail'));
    }
}
