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

    $pegawai = Auth::guard('pegawai')->user();
    $nip = $pegawai->id;

    $tanggalHariIni = Carbon::now()->format('Y-m-d');
    $tanggalKemarin = Carbon::yesterday()->format('Y-m-d');

    $absen = AbsenModel::where('nip', $nip)
        ->where('tgl_absen', $tanggalHariIni)
        ->first();

    if (!$absen) {
        $absen = AbsenModel::where('nip', $nip)
            ->where('tgl_absen', $tanggalKemarin)
            ->first();
    }

    if (!$absen) {
        return response()->json(['message' => 'Data absen tidak ditemukan'], 404);
    }

    $checkpoint = CheckModel::where('kode_unik', $request->kode_unik)->first();

    if (!$checkpoint) {
        return response()->json(['message' => 'QR Code tidak dikenali'], 404);
    }

    // ⛔ Validasi: jangan simpan jika sudah scan checkpoint sama dalam 1 jam terakhir
    $satuJamLalu = now()->subHour();

    $sudahScan = PatrolLogModel::where('karyawan_id', $pegawai->id)
        ->where('checkpoint_id', $checkpoint->id)
        ->whereBetween('waktu_scan', [$satuJamLalu, now()])
        ->exists();

    if ($sudahScan) {
        return response()->json([
            'message' => 'Checkpoint ini sudah dipindai dalam 1 jam terakhir'
        ], 409);
    }

    // ✅ Simpan foto base64
    $base64_image = $request->foto;
    $image_name = 'foto_' . uniqid() . '.jpg';

    $manager = new ImageManager(new Driver());
    $image = $manager->read($base64_image)->toJpeg(85);

    Storage::disk('public')->put('foto_patrol/' . $image_name, (string) $image);

    // ✅ Simpan log patroli
    PatrolLogModel::create([
        'karyawan_id' => $pegawai->id,
        'perusahaan' => $pegawai->perusahaan,
        'kantor' => $pegawai->nama_kantor,
        'checkpoint_id' => $checkpoint->id,
        'tgl_patrol' => $absen->tgl_absen,
        'waktu_scan' => now(),
        'keterangan' => $request->keterangan,
        'shift' => $absen->shifts->shift ?? '-', // hindari error jika shifts null
        'foto' => $image_name,
    ]);

    return response()->json(['message' => 'Patroli berhasil dicatat']);
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


// ===========================================================================================================================
    public function patroli()
    {
        $show = PatrolLogModel::where('perusahaan', Auth::guard('pegawai')->user()->perusahaan)
            ->where('kantor', Auth::guard('pegawai')->user()->nama_kantor)
            ->latest()
            ->limit(50)
            ->get();

        $checkpoints = CheckModel::where('perusahaan', Auth::guard('pegawai')->user()->perusahaan)
            ->where('kantor', Auth::guard('pegawai')->user()->nama_kantor)
            ->get();

        $nip = Auth::guard('pegawai')->user()->id;
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $tanggalKemarin = Carbon::yesterday()->format('Y-m-d');

        // Cek apakah ada absen hari ini
        $absen = AbsenModel::where('nip', $nip)
            ->where('tgl_absen', $tanggalHariIni)
            ->first();

        if (!$absen) {
            // Jika tidak ada, ambil absen hari kemarin jika belum jam_out
            $absen = AbsenModel::where('nip', $nip)
                ->where('tgl_absen', $tanggalKemarin)
                ->whereNull('jam_out')
                ->first();
        }

        return view('absen.patroli', [
            'show' => $show,
            'absen' => $absen,
            'belumAbsen' => !$absen, // true jika belum absen
            'checkpoints' => $checkpoints,
        ]);
    }


    public function patroliscan()
    {
        return view('absen.patrolicheck');
    }

    // Proses update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $checkpoint = CheckModel::findOrFail($id);
        $checkpoint->update([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Checkpoint berhasil diperbarui.');
    }

    // Proses hapus data
    public function destroy($id)
    {
        $checkpoint = CheckModel::findOrFail($id);
        $checkpoint->delete();

        return redirect()->back()->with('success', 'Checkpoint berhasil dihapus.');
    }
}
