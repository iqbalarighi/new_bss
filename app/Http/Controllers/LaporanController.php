<?php

namespace App\Http\Controllers;

use App\Models\LaporanModel;
use App\Models\SatkerModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Jenssegers\Agent\Agent;

class LaporanController extends Controller
{

public function perSatker(Request $request, $id)
{
    $query = LaporanModel::with('usr')
        ->where('satker', $id)
        ->when(Auth::user()->role == 1, fn($q) => $q->where('perusahaan', Auth::user()->perusahaan))
        ->when(Auth::user()->role == 3, fn($q) => $q->where('kantor', Auth::user()->kantor));

    // Filter pencarian (nama, no_lap, kantor)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('no_lap', 'like', "%$search%")
              ->orWhereHas('usr', function ($qu) use ($search) {
                  $qu->where('nama_lengkap', 'like', "%$search%")
                     ->orWhereHas('kantor', function ($que) use ($search) {
                         $que->where('nama_kantor', 'like', "%$search%");
                     });
              });
        });
    }

    // Filter berdasarkan tanggal
    if ($request->filled('tanggal')) {
        try {
            $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d');
            $query->whereDate('created_at', $tanggal);
        } catch (\Exception $e) {
            // Tanggal tidak valid, bisa diabaikan atau log
        }
    }

    $lapor = $query->latest()->paginate(10)->withQueryString();

    return view('laporan.admin.index', compact('lapor', 'id'));
}


    public function index(Request $request)
    {
            $query = LaporanModel::with('usr');

        if(Auth::user()->role == 0){
            // admin: akses semua
        } elseif(Auth::user()->role == 1) {
            $query->where('perusahaan', Auth::user()->perusahaan);
        } elseif (Auth::user()->role == 3) {
            $query->where('kantor', Auth::user()->kantor);
        } else {
            $query->where('user_id', Auth::user()->id);
        }


        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_lap', 'like', "%$search%")
                  ->orWhereHas('usr', function ($qu) use ($search) {
                      $qu->where('nama_lengkap', 'like', "%$search%")
                        ->orWhereHas('kantor', function ($que) use ($search) {
                            $que->where('nama_kantor', 'like', "%$search%");
                        });
                  });
            });
        }

        if ($request->filled('tanggal')) {
            try {
                $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d');
                $query->whereDate('created_at', $tanggal);
            } catch (\Exception $e) {
                // Optional: log or ignore invalid date
            }
        }

        $lapor = $query->latest()->paginate(10)->withQueryString();

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
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096', // Validasi file gambar
        ]);

        // Generate no_lap
        $noLap = LaporanModel::generateNoLap();
        $files = $request->file('foto');
        $fotoNames = [];

        // Handle upload foto
    if ($files != null) {
       // $directory = base_path('../public_html/storage/laporan/admin/' . $noLap); // Buat direktori penyimpanan live instance
        $directory = public_path('storage/laporan/admin/' . $noLap); // Buat direktori penyimpanan

        // Buat folder jika belum ada
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $manager = new ImageManager(new Driver()); // Inisialisasi di luar loop

        foreach ($files as $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $fotoName = Str::random(20) . '.' . $extension;
            $image = $manager->read($file->getPathname());
            // Resize skala
            $image->scale(800, null); // Tanpa named parameter!
            $image->toJpeg(75)->save($directory . '/' . $fotoName);// Simpan sebagai JPEG dengan kualitas 75%
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

    public function detail($id, $ids)
    {
        $detail = LaporanModel::findOrFail($ids);
        $satker = SatkerModel::findOrFail($id);

       return view('laporan.admin.detail', compact('detail', 'id', 'satker'));
    }

    public function savepdf($id, $ids)
    {
        $detail = LaporanModel::findOrFail($ids);
        $satker = SatkerModel::findOrFail($id);
        $agent = new Agent();

        $pdf = Pdf::loadView('laporan.admin.savepdf', compact('detail', 'satker'))
                  ->setPaper('A4', 'portrait');
        if ($agent->isMobile()){
            return $pdf->download('Laporan Kegiatan Admin '.$detail->no_lap.'.pdf');
        } else {
            return $pdf->stream('Laporan Kegiatan Admin '.$detail->no_lap.'.pdf');
        }
    }


    public function edit($id, $ids)
    {
        $edit = LaporanModel::findOrFail($id);

        return view('laporan.admin.edit', compact('edit'));
    }

public function destroy($id, $ids)
{
    $laporan = LaporanModel::findOrFail($id);

    // Path folder foto
    // $folderPath = base_path('../public_html/storage/laporan/admin/' . $laporan->no_lap);
    $folderPath = public_path('storage/laporan/admin/' . $laporan->no_lap);

    // Hapus semua file di dalam folder
    if (File::exists($folderPath)) {
        File::deleteDirectory($folderPath);
    }

    // Hapus data laporan dari database
    $laporan->delete();

    return response()->json(['success' => true]);
}

    public function hapusFoto(Request $request, $id)
    {
        $laporan = LaporanModel::findOrFail($id);
        $fotoToDelete = $request->foto;

        if ($laporan->foto) {
            $fotos = explode('|', $laporan->foto);
            if (($key = array_search($fotoToDelete, $fotos)) !== false) {
                unset($fotos[$key]);

                // Hapus file fisik dari storage
                // $filePath = base_path('../public_path/storage/laporan/admin/' . $laporan->no_lap . '/' . $fotoToDelete);
                $filePath = public_path('storage/laporan/admin/' . $laporan->no_lap . '/' . $fotoToDelete);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // Update field foto di database
                $laporan->foto = count($fotos) > 0 ? implode('|', $fotos) : null;
                $laporan->save();
            }
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanModel::findOrFail($id);
        $files = $request->file('foto');
        $fotoNames = [];

        // Handle upload foto
    if ($files != null) {
       // $directory = base_path('../public_html/storage/laporan/admin/' . $noLap); // Buat direktori penyimpanan live instance
        $directory = public_path('storage/laporan/admin/' . $laporan->no_lap); // Buat direktori penyimpanan

        // Buat folder jika belum ada
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $manager = new ImageManager(new Driver()); // Inisialisasi di luar loop

        foreach ($files as $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $fotoName = Str::random(20) . '.' . $extension;
            $image = $manager->read($file->getPathname());
            // Resize skala
            $image->scale(800, null); // Tanpa named parameter!
            $image->toJpeg(75)->save($directory . '/' . $fotoName);// Simpan sebagai JPEG dengan kualitas 75%
            $fotoNames[] = $fotoName;
        }

            if ($laporan->foto == null) {
                $tambah = implode('|', $fotoNames);
            } else {
                $tambah = $laporan->foto.'|'.implode('|', $fotoNames);
            }

            $laporan->foto = $tambah;
    }


        $laporan->personil = $request->personil;
        $laporan->kegiatan = $request->kegiatan;
        $laporan->keterangan = $request->keterangan;
        $laporan->save();

        return response()->json(['success' => true]);
    }
}
