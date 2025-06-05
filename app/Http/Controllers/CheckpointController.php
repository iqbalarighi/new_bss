<?php

namespace App\Http\Controllers;

use App\Models\AbsenModel;
use App\Models\CheckModel;
use App\Models\PatrolLogModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use carbon\Carbon;

class CheckpointController extends Controller
{
   public function index()
   {
$user = Auth::user();

       if ($user->role == 0) {
    // Admin: tampilkan semua data
            $show = CheckModel::paginate(10);
        } elseif ($user->role == 1) {
            // Role 1 atau 3: filter berdasarkan perusahaan
            $show = CheckModel::where('perusahaan', $user->perusahaan)->latest()->paginate(10);
        } elseif ($user->role == 3) {
            // Role 1 atau 3: filter berdasarkan perusahaan dan kantor
            $show = CheckModel::where('perusahaan', $user->perusahaan)->where('kantor', $user->kantor)->latest()->paginate(10);
        }

       return view('master.patrolarea', compact('show'));
   }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string',
        ]);

        $kode_unik = Str::uuid();
        
// buat filter lagi untuk beda role

    if(Auth::user()->role == 0) {
        $perus = '';
        $kantor = '';
    }  elseif(Auth::user()->role == 1 ) {
        $perus = Auth::user()->perusahaan;
        $kantor = $request->kantor;
    } elseif(Auth::user()->role == 3) {
        $perus = Auth::user()->perusahaan;
        $kantor = Auth::user()->kantor;
    } 

        $checkpoint = CheckModel::create([
            'perusahaan' => $perus,
            'kantor'=>  $kantor,
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'kode_unik' => $kode_unik,
        ]);

        return redirect()->route('checkpoints.index')->with('success', 'Checkpoint ditambahkan');
    }

    public function showQr(CheckModel $checkpoint)
    {
        $qrcode = QrCode::size(250)->generate($checkpoint->kode_unik);
        return view('checkpoints.qrcode', compact('checkpoint', 'qrcode'));
    }

        public function scan(Request $request)
    {
        $request->validate([
            'kode_unik' => 'required|string',
            'keterangan' => 'required|string',
            'foto' => 'required|string',
        ]);

        $nip = Auth::guard('pegawai')->user()->id;
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $tanggalKemarin = Carbon::yesterday()->format('Y-m-d');
        // Cek apakah ada absen hari ini

        $absen = AbsenModel::where('nip', $nip)
            ->where('tgl_absen', $tanggalHariIni)
            ->first();

        if (!$absen) {
            // Jika tidak ada, ambil absen hari kemarin
            $absen = AbsenModel::where('nip', $nip)
                ->where('tgl_absen', $tanggalKemarin)
                ->first();
        }

        $checkpoint = CheckModel::where('kode_unik', $request->kode_unik)->first();

        if (!$checkpoint) {
            return response()->json(['message' => 'QR Code tidak dikenali'], 404);
        }

        // Simpan foto base64 ke storage
            $base64_image = $request->foto;
            $image_name = 'foto_' . uniqid() . '.jpg';

            // Decode base64 dan simpan ke storage/app/public/foto_patrol
            $manager = new ImageManager(new Driver());
            $image = $manager->read($base64_image)->toJpeg(85);

            // Simpan ke storage menggunakan Laravel Storage
            Storage::disk('public')->put('foto_patrol/' . $image_name, (string) $image);

        // Simpan log
        PatrolLogModel::create([
            'user_id' => auth()->guard('pegawai')->id(),
            'perusahaan' => Auth::guard('pegawai')->user()->perusahaan,
            'kantor' => Auth::guard('pegawai')->user()->nama_kantor,
            'checkpoint_id' => $checkpoint->id,
            'waktu_scan' => now(),
            'keterangan' => $request->keterangan,
            'shift' => $absen->shifts->shift,
            'foto' => $image_name,
        ]);

        return response()->json(['message' => 'Patroli berhasil dicatat']);
    }



    public function patroli()
    {
        $show = PatrolLogModel::where('perusahaan', Auth::guard('pegawai')->user()->perusahaan)->latest()->get();

        $nip = Auth::guard('pegawai')->user()->id;
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $tanggalKemarin = Carbon::yesterday()->format('Y-m-d');
        // Cek apakah ada absen hari ini

        $absen = AbsenModel::where('nip', $nip)
            ->where('tgl_absen', $tanggalHariIni)
            ->first();

        if (!$absen) {
            // Jika tidak ada, ambil absen hari kemarin
            $absen = AbsenModel::where('nip', $nip)
                ->where('tgl_absen', $tanggalKemarin)
                ->first();
        }

        return view('absen.patroli', [
            'show' => $show,
        'absen' => $absen,
        'belumAbsen' => !$absen, // true jika belum absen
    ]);
    }

    public function patroliscan()
    {
        return view('absen.patrolicheck');
    }

    public function getCheckpointInfo(Request $request)
    {
        $request->validate(['kode_unik' => 'required|string']);

        $checkpoint = CheckModel::where('kode_unik', $request->kode_unik)->first();

        if (!$checkpoint) {
            return response()->json(['message' => 'QR Code tidak valid'], 404);
        }

        return response()->json([
            'checkpoint' => $checkpoint,
        ]);
    }

}
