<?php

namespace App\Http\Controllers;

use App\Models\AbsenModel;
use App\Models\IzinabsenModel;
use App\Models\LaporanModel;
use App\Models\PegawaiModel;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $absens = AbsenModel::with('pegawai')->where('nip', $id)->where('tgl_absen', 'LIKE', '%'.carbon::now()->format('m').'%')->latest()->get();
        
        $rekap = AbsenModel::where('nip', $id)
                ->where('tgl_absen', 'LIKE',  '%'.carbon::now()->format('Y-m').'%')
                ->selectRaw('
                    COUNT(nip) as jmlhadir,
                    SUM(CASE WHEN jam_in > "'.($pegawai->shifts->jam_masuk).'" THEN 1 ELSE 0 END) as jmltelat
                ')
                ->first();

        $leaderboard = AbsenModel::with('pegawai.perusa', 'pegawai.jabat')
                ->where('tgl_absen', $harini)
                ->where('perusahaan', Auth::guard('pegawai')->user()->perusahaan)
                ->where('kantor', Auth::guard('pegawai')->user()->nama_kantor)
                ->orderBy('jam_in')
                ->get();

        $rekapizin = IzinabsenModel::where('nip', $id)
                    ->where('tanggal', 'LIKE', '%'.Carbon::now()->format('Y-m').'%')
                    ->selectRaw("
                        SUM(CASE WHEN jenis_izin = 'i' THEN 1 ELSE 0 END) as izin, 
                        SUM(CASE WHEN jenis_izin = 's' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN jenis_izin = 'c' THEN 1 ELSE 0 END) as cuti
                    ")
                    ->where('status_approve', 1)
                    ->first();

        return view('absen.index', compact('pegawai', 'absen', 'absens', 'rekap', 'leaderboard', 'rekapizin'));
    }

    public function create()
    {
        $harini = date('Y-m-d');
        $nip_id = Auth::guard('pegawai')->user()->id;
        $cek = AbsenModel::where('tgl_absen', $harini)->where('nip', $nip_id)->count();
        $cek2 = AbsenModel::where('tgl_absen', $harini)->where('nip', $nip_id)->first();

        if($cek2 == null){
        $absenTerakhir = AbsenModel::where('nip', $nip_id)
            ->where('tgl_absen', '<', $harini)
            ->whereNull('jam_out')
            ->latest()
            ->orderByDesc('created_at')
            ->first();
        } else {
            $absenTerakhir = null;
        }
        

            // Cek apakah absen terakhir belum absen pulang
            // if ($absenTerakhir && $absenTerakhir->jam_out === null) {
            //     return redirect()->back()->with('error', 'Anda belum melakukan absen pulang pada tanggal ' . $absenTerakhir->tgl_absen . '. Harap selesaikan terlebih dahulu.');
            // }

        $pegawai = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat' )->findOrFail($nip_id);

        return view('absen.create', compact('pegawai', 'cek', 'cek2', 'absenTerakhir'));
    }

    public function store(Request $request)
    {
        // dd($request->confirm != null);
        $nip = Auth::guard('pegawai')->user()->nip;
        $nip_id = Auth::guard('pegawai')->user()->id;
        $shift_id = Auth::guard('pegawai')->user()->shift;
        $tgl_absen = date("Y-m-d");
        $jam_absen = date("H:i:s");
        $jam_foto = date("His");
        $lokasi = $request->lokasi;
        $image = $request->image;
        $folderPath = ('storage/absensi/' . $nip . '/');
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

if ($request->confirm != null) {
        // Ambil absen terakhir sebelum hari ini yang belum pulang
        $absenSebelumnya = AbsenModel::where('nip', $nip_id)
            ->where('tgl_absen', '<', $tgl_absen)
            ->whereNull('jam_out')
            ->latest()
            ->orderByDesc('tgl_absen')
            ->first();

        if ($absenSebelumnya != null) {
            // Auto-isi absen pulang dengan jam sekarang untuk absen sebelumnya
            $fileNameOut = $absenSebelumnya->tgl_absen . "-" . $jam_foto . "-out.png";
            $fileOutPath = $folderPath . $fileNameOut;

            $abs = AbsenModel::where('id', $absenSebelumnya->id)->update([
                'jam_out' => $jam_absen,
                'foto_out' => $fileNameOut,
                'lokasi_out' => $lokasi,
            ]);

            // Optional: log atau simpan informasi bahwa ini absen pulang otomatis

            if ($abs) {
            file_put_contents($fileOutPath, $image_base64);
                echo "absplg|Terima Kasih, Absen Pulang Berhasil|out";
                return;
            } else {
                echo "error|Gagal menyimpan absen pulang";
                return;
            }
        }
}
        // Cek apakah hari ini sudah absen masuk
        $cek = AbsenModel::where('tgl_absen', $tgl_absen)->where('nip', $nip_id)->count();
        if ($cek > 0) {
            // Proses absen pulang
            $fileName = $tgl_absen . "-" . $jam_foto . "-out.png";
            $file = $folderPath . $fileName;

            $update = AbsenModel::where('nip', $nip_id)->where('tgl_absen', $tgl_absen)->update([
                'jam_out' => $jam_absen,
                'foto_out' => $fileName,
                'lokasi_out' => $lokasi,
            ]);

            if ($update) {
                file_put_contents($file, $image_base64);
                echo "success|Terima Kasih, Absen Pulang Berhasil|out";
            } else {
                echo "error|Gagal menyimpan absen pulang";
            }

        } else {
            // Proses absen masuk
            $fileName = $tgl_absen . "-" . $jam_foto . "-in.png";
            $file = $folderPath . $fileName;

            $simpan = AbsenModel::create([
                'nip' => $nip_id,
                'shift' => $shift_id,
                'perusahaan' => $id_perus,
                'kantor' => $id_kan,
                'tgl_absen' => $tgl_absen,
                'jam_in' => $jam_absen,
                'foto_in' => $fileName,
                'lokasi_in' => $lokasi,
            ]);

            if ($simpan) {
                file_put_contents($file, $image_base64);
                echo "success|Terima Kasih, Absen Masuk Berhasil|in";
            } else {
                echo "error|Gagal menyimpan absen masuk";
            }
        }
    }


    public function profile()
    {
        $nip = Auth::guard('pegawai')->user()->nip;
        $profile = PegawaiModel::where('nip', $nip)->first();

        return view('absen.profile', compact('profile'));
    }

    // public function profilimage(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096', // Maksimal 4MB
    //     ]);

    //     try {
    //         // Ambil file dari request
    //         $file = $request->file('profile_image');

    //         $user = Auth::guard('pegawai')->user();

    //         if ($user->foto != null) {
    //                 File::deleteDirectory(public_path('storage/foto_pegawai/'.$user->nip));
    //             }


    //     $directory = public_path('storage/foto_pegawai/admin/' . $user->nip); // Buat direktori penyimpanan

    //     // Buat folder jika belum ada
    //     if (!File::exists($directory)) {
    //         File::makeDirectory($directory, 0755, true);
    //     }


    //         // Buat nama file unik
    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

    //         // Simpan file ke direktori public/profile-images
    //         $filePath = $file->storeAs('public/foto_pegawai/'.$user->nip.'/', $fileName);

    //         // URL file yang disimpan
    //         $fileUrl = Storage::url($filePath);

    //         $user->foto = $fileName;
    //         $user->save();

    //         // Respon jika berhasil
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Foto profil berhasil diunggah.',
    //             'file_url' => $fileUrl,
    //         ], 200);

    //     } catch (\Exception $e) {
    //         // Respon jika gagal
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan saat mengunggah: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    // versi live
    public function profilimage(Request $request)
    {
        // Validasi input
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096', // Maksimal 4MB
        ]);

        try {
            // Ambil file dari request
$file = $request->file('profile_image');
            $user = Auth::guard('pegawai')->user();
            
            // Path ke folder penyimpanan di public_html
            $destinationPath = public_path('storage/foto_pegawai/' . $user->nip . '/');
            
            // Hapus folder lama jika ada
            if ($user->foto != null && File::exists($destinationPath)) {
                File::deleteDirectory($destinationPath);
            }
            
            // Buat nama file unik
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Buat folder jika belum ada
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            
            // Simpan file ke folder tujuan
            $file->move($destinationPath, $fileName);
            
            // Simpan nama file ke database
            $user->foto = $fileName;
            $user->save();
            
            // Buat URL file (akses publik)
            $fileUrl = asset('storage/foto_pegawai/' . $user->nip . '/' . $fileName);
            
            // Respon jika berhasil
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diunggah.',
                'file_url' => $fileUrl,
            ], 200);

        } catch (\Exception $e) {
            // Respon jika gagal
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunggah: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateNama(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        try {
            $user = Auth::guard('pegawai')->user(); // Ambil user yang sedang login
            $user->nama_lengkap = $request->nama;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Nama berhasil diperbarui.',
                'name' => $user->nama_lengkap,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui nama. Silakan coba lagi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateNowa(Request $request)
    {
        try {
            $user = Auth::guard('pegawai')->user(); // Ambil user yang sedang login
            $user->no_hp = $request->nowa;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Nomor telepon berhasil diperbarui.',
                'name' => $user->no_hp,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui nomor telepon. Silakan coba lagi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePass(Request $request)
    {
        // Validasi input
       $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|max:100|confirmed',
        ], [
            'old_password.required' => 'Password lama wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        $user = Auth::guard('pegawai')->user();

        // Cek password lama
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Password lama salah'
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Password berhasil diubah'
        ], 200);
        
    }

    public function histori()
    {
        return view('absen.histori');
    }

    public function gethistori(Request $request)
    {
       $bultah = $request->bultah;

       $get = AbsenModel::with('pegawai')
       ->where('nip', Auth::guard('pegawai')->user()->id)
       ->where('tgl_absen', 'LIKE', '%'.$bultah.'%')
       ->orderBy('tgl_absen')
       ->get();

       return view('absen.gethistori', compact('get'));
    }

    public function izin()
    {
        $nip_id = Auth::guard('pegawai')->user()->id;
        $izin = IzinabsenModel::where('nip', $nip_id)->get();

        return view('absen.izin', compact('izin'));
    }

    public function formizin()
    {
        return view('absen.formizin');
    }

    // public function formizinsimpan(Request $request)
    // {
    //     $request->validate([
    //         'tanggal' => 'required|date',
    //         'jenisIzin' => 'required|in:i,s,c',
    //         'keterangan' => 'required|string|max:255',
    //         'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $user = Auth::guard('pegawai')->user();

    //     $data = [
    //         'nip' => $user->id,
    //         'perusahaan' => $user->perusahaan,
    //         'nama_kantor' => $user->nama_kantor,
    //         'tanggal' => $request->tanggal,
    //         'jenis_izin' => $request->jenisIzin,
    //         'keterangan' => $request->keterangan,
    //     ];

    //     if ($request->hasFile('buktiFoto')) {
    //         $filename = Str::random(40) . '.' . $request->file('buktiFoto')->getClientOriginalExtension();
    //         $path = $request->file('buktiFoto')->storeAs("bukti_izin/$user->nip", $filename, 'public');
    //         $data['foto'] = $filename;
    //     }

    //     // Simpan ke database (sesuai model yang digunakan, contoh: Izin)
    //     IzinabsenModel::create($data);

    //     return redirect('absen/izin')->with('success', 'Data izin berhasil disimpan.');
    // }

    // versi live

    public function formizinsimpan(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenisIzin' => 'required|in:i,s,c',
            'keterangan' => 'required|string|max:255',
            'buktiFoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::guard('pegawai')->user();

        // Cek apakah sudah ada izin dengan tanggal dan nip yang sama
        $cekIzin = IzinabsenModel::where('nip', $user->id)
                    ->whereDate('tanggal', $request->tanggal)
                    ->exists();

        if ($cekIzin) {
            return redirect()->back()->with('error', 'Data izin untuk tanggal tersebut sudah ada.');
        }

        $data = [
            'nip' => $user->id,
            'perusahaan' => $user->perusahaan,
            'nama_kantor' => $user->nama_kantor,
            'tanggal' => $request->tanggal,
            'jenis_izin' => $request->jenisIzin,
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('buktiFoto')) {
            $file = $request->file('buktiFoto');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('storage/bukti_izin/' . $user->nip . '/');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $data['foto'] = $filename;
        }

        IzinabsenModel::create($data);

        return redirect('absen/izin')->with('success', 'Data izin berhasil disimpan.');
    }

    public function lapor()
    {
        $lapor = LaporanModel::where('user_id', Auth::guard('pegawai')->user()->id)
                ->where('satker', Auth::guard('pegawai')->user()->satker)
                ->get();

        return view('absen.laporan', compact('lapor'));
    }

    public function laporan(Request $request)
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
            'perusahaan' => Auth::guard('pegawai')->user()->perusahaan,
            'kantor' => Auth::guard('pegawai')->user()->kantor,
            'dept' => Auth::guard('pegawai')->user()->dept,
            'satker' => Auth::guard('pegawai')->user()->satker,
            'jabatan' => Auth::guard('pegawai')->user()->jabatan,
            'user_id' => Auth::guard('pegawai')->user()->id,
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
}