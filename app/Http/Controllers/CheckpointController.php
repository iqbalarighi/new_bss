<?php

namespace App\Http\Controllers;

use App\Models\CheckModel;
use App\Models\PatrolLogModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CheckpointController extends Controller
{
   public function index()
   {
       $show = CheckModel::paginate(10);
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

        $checkpoint = CheckModel::create([
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

        $checkpoint = CheckModel::where('kode_unik', $request->kode_unik)->first();

        if (!$checkpoint) {
            return response()->json(['message' => 'QR Code tidak dikenali'], 404);
        }

        // Simpan foto base64
            $base64_image = $request->foto;
            $image_name = 'foto_' . uniqid() . '.jpg';
            $path = public_path('uploads/foto_patrol');
            if (!file_exists($path)) mkdir($path, 0777, true);

            // FIX: Inisialisasi ImageManager secara manual
            $manager = new ImageManager(new Driver());
            $manager->read($base64_image)->toJpeg(85)->save($path . '/' . $image_name);

        // Simpan log
        PatrolLogModel::create([
            'user_id' => auth()->guard('pegawai')->id(),
            'checkpoint_id' => $checkpoint->id,
            'waktu_scan' => now(),
            'keterangan' => $request->keterangan,
            'foto' => $image_name,
        ]);

        return response()->json(['message' => 'Patroli berhasil dicatat']);
    }



    public function patroli()
    {
        return view('absen.patroli');
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
