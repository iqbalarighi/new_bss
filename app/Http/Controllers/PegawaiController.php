<?php

namespace App\Http\Controllers;

use App\Exports\LemburBulananExport;
use App\Exports\LemburExport;
use App\Exports\PresensiExport;
use App\Exports\RekapAbsensiExport;
use App\Models\AbsenModel;
use App\Models\DeptModel;
use App\Models\IzinabsenModel;
use App\Models\JabatanModel;
use App\Models\KantorModel;
use App\Models\LemburModel;
use App\Models\PegawaiModel;
use App\Models\PerusahaanModel;
use App\Models\SatkerModel;
use App\Models\ShiftModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Jenssegers\Agent\Agent;
use Maatwebsite\Excel\Facades\Excel;

class PegawaiController extends Controller
{
    public function index()
    {

    if(Auth::user()->role === 0){
        $pegawais = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat')
        ->paginate(15);

    return view('pegawai.index', compact('pegawais'));
    }

    if(Auth::user()->role === 1){
        $pegawais = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat')
        ->where('perusahaan', Auth::user()->perusahaan)
        ->paginate(15);

    return view('pegawai.index', compact('pegawais'));
    } 

    if(Auth::user()->role === 3){
        $pegawais = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat')
        ->where('perusahaan', Auth::user()->perusahaan)
        ->where('nama_kantor', Auth::user()->kantor)
        ->paginate(15);

    return view('pegawai.index', compact('pegawais'));
    }

        
    }

    public function input()
    {
        if(Auth::user()->role === 0){
        $tenant = PerusahaanModel::get();
        $kantor = KantorModel::get();
        $jabatan = JabatanModel::get();
        $satker = SatkerModel::get();
        $shift = ShiftModel::get();
        } else {
        $id = Auth::user()->perusahaan;
        $knt = Auth::user()->kantor;

        $tenant = PerusahaanModel::get();
        $kantor = KantorModel::where('perusahaan', $id)->where('id', $knt)->get();
        $jabatan = JabatanModel::where('perusahaan', $id)->get();
        $satker = SatkerModel::where('perusahaan', $id)->get();
        $shift = ShiftModel::where('id', $knt)->get();
        }


        return view('pegawai.input', compact('tenant', 'kantor', 'jabatan', 'satker', 'shift'));
    }

    public function edit($id)
    {
        $pegawai = PegawaiModel::findOrFail($id);
        $tenant = PerusahaanModel::get();

            $kantorList = KantorModel::where('perusahaan', $pegawai->perusahaan)->get();
            $departemenList = DeptModel::where('perusahaan', $pegawai->perusahaan)->where('nama_kantor', $pegawai->nama_kantor)->get();
            $satkerList = SatkerModel::where('perusahaan', $pegawai->perusahaan)->where('kantor', $pegawai->nama_kantor)->where('dept_id', $pegawai->dept)->get();
            $jabatanList = JabatanModel::where('perusahaan', $pegawai->perusahaan)->where('kantor_id', $pegawai->nama_kantor)->where('dept_id', $pegawai->dept)->where('satker_id', $pegawai->satker)->get();
            $shift = ShiftModel::where('satker_id', $pegawai->satker)->get();



        return view('pegawai.edit', compact('pegawai', 'tenant', 'kantorList', 'jabatanList', 'satkerList', 'departemenList', 'shift'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:karyawan',
            'password' => 'required|string|min:6',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string',
            'alamat_domisili' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'jabatan' => 'required|string',
            'dept' => 'required|string',
            'bpjs_tk' => 'required|string',
            'bpjs_kesehatan' => 'required|string',
            'kontak_darurat' => 'required|string|max:15',
            'satker' => 'required|string',
            'statpegawai' => 'required|string|in:Tetap,Kontrak',
            'status' => 'required|string|in:Aktif,Tidak Aktif',
            'foto' => 'image|mimes:jpeg,png,jpg|max:8000',
        ], [
            'nama.required' => 'Nama pegawai wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat_domisili.required' => 'Alamat domisili wajib diisi.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'dept.required' => 'Departemen wajib diisi.',
            'bpjs_tk.required' => 'BPJS TK wajib diisi.',
            'bpjs_kesehatan.required' => 'BPJS Kesehatan wajib diisi.',
            'kontak_darurat.required' => 'Kontak darurat wajib diisi.',
            'satker.required' => 'Satker wajib diisi.',
            'statpegawai.required' => 'Status pegawai wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran gambar maksimal 4MB.',
        ]);



if(Auth::user()->role === 1){
        $id = Auth::user()->perusahaan;
         $kantor = $request->kantor;
} 
if(Auth::user()->role === 3){
        $id = Auth::user()->perusahaan;
        $kantor = Auth::user()->kantor;
}
if(Auth::user()->role === 0){
        $id = $request->perusahaan;
        $kantor = $request->kantor;
}

$foto = $request->file('foto');

if ($foto !== null) {
    // Buat nama file acak
    $fotoNama = Str::random(20) . '.' . $foto->getClientOriginalExtension();
    $folder = 'foto_pegawai/' . $request->nip;

    // Buat instance ImageManager
    $manager = new ImageManager(new Driver());
    $image = $manager->read($foto->getPathname());

    // Resize opsional (misal lebar max 600px)
    $image->scale(width: 600);

    // Simpan sebagai binary string ke Laravel Storage
    Storage::disk('public')->put(
        $folder . '/' . $fotoNama,
        (string) $image->encode() // encode ke format file asli
    );
} else {
    $fotoNama = null;
}
        PegawaiModel::create([
            'perusahaan' => $id,
            'nama_lengkap' => $request->nama,
            'nip' => $request->nip,
            'dept' => $request->dept,
            'password' => Hash::make($request->password),
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'domisili' => $request->alamat_domisili,
            'no_hp' => $request->no_telepon,
            'jabatan' => $request->jabatan,
            'bpjs_tk' => $request->bpjs_tk,
            'bpjs_sehat' => $request->bpjs_kesehatan,
            'ko_drat' => $request->kontak_darurat,
            'nama_kantor' => $kantor,
            'satker' => $request->satker,
            'statpegawai' => $request->statpegawai,
            'status' => $request->status,
            'foto' => $fotoNama,
        ]);


        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'nip' => 'required|string|max:50|unique:karyawan,nip,' . $id,
        'password' => 'nullable|string|min:6',
        'tgl_lahir' => 'required|date',
        'alamat' => 'required|string',
        'alamat_domisili' => 'required|string',
        'no_telepon' => 'required|string|max:15',
        'jabatan' => 'required|string',
        'dept' => 'required|string',
        'bpjs_tk' => 'required|string',
        'bpjs_kesehatan' => 'required|string',
        'kontak_darurat' => 'required|string|max:15',
        'satker' => 'required|string',
        'statpegawai' => 'required|string|in:Tetap,Kontrak',
        'status' => 'required|string|in:Aktif,Tidak Aktif',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:8000',
    ]);

    $pegawai = PegawaiModel::findOrFail($id);

    // Dapatkan nilai perusahaan & kantor berdasarkan role
    if (Auth::user()->role === 1) {
        $perusahaan = Auth::user()->perusahaan;
        $kantor = $request->kantor;
    } elseif (Auth::user()->role === 3) {
        $perusahaan = Auth::user()->perusahaan;
        $kantor = Auth::user()->kantor;
    } else {
        $perusahaan = $request->perusahaan;
        $kantor = $request->kantor;
    }

    // Upload foto jika ada
    if ($request->hasFile('foto')) {
    $foto = $request->file('foto');
    $fotoNama = Str::random(20) . '.' . $foto->getClientOriginalExtension();
    $folder = 'foto_pegawai/' . $request->nip;

    // Proses gambar menggunakan Intervention Image
    $manager = new ImageManager(new Driver());
    $image = $manager->read($foto->getPathname());

    // Resize opsional (misalnya, lebar maks. 600px)
    $image->scale(width: 600);

    // Simpan hasil encode ke Laravel Storage (storage/app/public)
    Storage::disk('public')->put(
        $folder . '/' . $fotoNama,
        (string) $image->encode() // encode ke format asli
    );
} else {
    $fotoNama = $pegawai->foto; // Tetap gunakan foto lama jika tidak upload
}

    $pegawai->update([
        'perusahaan' => $perusahaan,
        'nama_lengkap' => $request->nama,
        'nip' => $request->nip,
        'dept' => $request->dept,
        'shift' => $request->shift,
        'password' => $request->password ? Hash::make($request->password) : $pegawai->password,
        'tgl_lahir' => $request->tgl_lahir,
        'alamat' => $request->alamat,
        'domisili' => $request->alamat_domisili,
        'no_hp' => $request->no_telepon,
        'jabatan' => $request->jabatan,
        'bpjs_tk' => $request->bpjs_tk,
        'bpjs_sehat' => $request->bpjs_kesehatan,
        'ko_drat' => $request->kontak_darurat,
        'nama_kantor' => $kantor,
        'satker' => $request->satker,
        'statpegawai' => $request->statpegawai,
        'status' => $request->status,
        'foto' => $fotoNama,
    ]);

    return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
}

    public function detail($id)
    {

        $detail = PegawaiModel::findOrFail($id);

        return view('pegawai.detail', compact('detail'));
    }


    public function cekNip(Request $request)
    {
        $nip = $request->nip;
        // $exists = DB::table('pegawai')->where('nip', $nip)->exists();
        $exists = PegawaiModel::where('nip', $nip)->exists();

        return response()->json(['exists' => $exists]);
    }


    public function ubahpass(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'pegawai_id' => 'required|exists:karyawan,id'
        ]);

        try {
            $pegawai = PegawaiModel::findOrFail($request->pegawai_id);
            $pegawai->password = Hash::make($request->password);
            $pegawai->save();

            return response()->json(['message' => 'Password berhasil diperbarui'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui password'], 500);
        }
    }


    // public function absensi(Request $request)
    // {
        
    //     if (Auth::user()->role == 0) {
    //         $absen = AbsenModel::latest()->paginate(10);
    //     } elseif (Auth::user()->role == 1) {
    //         $comp = Auth::user()->perusahaan;

    //             $absen = AbsenModel::where('perusahaan', $comp)
    //                 ->latest()
    //                 ->paginate(10); // Sesuaikan kolomnya

    //     } elseif (Auth::user()->role == 3) {
    //         $comp = Auth::user()->perusahaan;
    //         $kantor = Auth::user()->kantor;

    //             $absen = AbsenModel::where('perusahaan', $comp)
    //                 ->where('kantor', $kantor)
    //                 ->latest()
    //                 ->paginate(10); // Sesuaikan kolomnya

    //     } 

    //     return view('pegawai.absensi', compact('absen'));
    // }

    public function absensi(Request $request)
    {
        $bultah = $request->tanggal; // Format: YYYY-MM

        if (Auth::user()->role == 0) {
            if($bultah == ""){
            $absen = AbsenModel::latest()->paginate(10);
            } else {
            $absen = AbsenModel::where('tgl_absen', 'LIKE', '%'.$bultah.'%')
                ->latest()
                ->paginate(10); // Sesuaikan kolomnya

                $absen->appends(['bultah' => $bultah]);
            }
        } elseif (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $kantor = $request->kantor;

            if($bultah == ""){
            $absen = AbsenModel::where('perusahaan', $comp)
                ->latest()
                ->paginate(10);
                } else {
                $absen = AbsenModel::where('perusahaan', $comp)
                    ->where('tgl_absen', 'LIKE', '%'.$bultah.'%')
                    ->latest()
                    ->paginate(10); // Sesuaikan kolomnya

                    $absen->appends(['bultah' => $bultah]);
                }
        } elseif (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;

            if($bultah == ""){
            $absen = AbsenModel::where('perusahaan', $comp)
                ->where('kantor', $kantor)
                ->latest()
                ->paginate(10);
                } else {
                $absen = AbsenModel::where('perusahaan', $comp)
                    ->where('kantor', $kantor)
                    ->where('tgl_absen', 'LIKE', '%'.$bultah.'%')
                    ->latest()
                    ->paginate(10); // Sesuaikan kolomnya

                    $absen->appends(['bultah' => $bultah]);
                }
        } 

        return view('pegawai.absensi', compact('absen'));
    }

    public function lapor() //untuk role 1 dan 3 nya masih ngebug
    {
        if (Auth::user()->role == 0) {
            $karyawans = PegawaiModel::all();
            $kantors = KantorModel::all();
            $tabul = PegawaiModel::get('created_at')->first();

        return view('pegawai.laporan', compact('karyawans', 'kantors', 'tabul'));
        } elseif (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $karyawans = PegawaiModel::where('perusahaan', $comp)->get();
            $kantors = KantorModel::where('perusahaan', $comp)->get();
            $tabul = PegawaiModel::get('created_at')->first();

        return view('pegawai.laporan', compact('karyawans', 'kantors', 'tabul'));
        } elseif (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $tabul = PegawaiModel::get('created_at')->first();
            $karyawans = PegawaiModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();
            $depts = DeptModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();

        return view('pegawai.laporan', compact('karyawans', 'tabul', 'depts'));
        } 

    }

    public function preview(Request $request)
    {
        $periode = $request->periode;
        $id = $request->pegawais;

        $pegawai = PegawaiModel::findOrFail($id);

        $absen = AbsenModel::with(['pegawai', 'shifts'])
            ->where('nip', $pegawai->id)
            ->where('tgl_absen', 'LIKE', '%' . $periode . '%')
            ->get();

    $agent = new Agent();

    $pdf = Pdf::loadView('pegawai.preview', compact('pegawai', 'absen', 'periode'))
                  ->setPaper('A4', 'portrait');

        if ($request->action == "cetak") {
            if ($agent->isMobile()){
                return $pdf->download('Laporan Absensi Pegawai '.$pegawai->nama_lengkap.'.pdf');
            } else {
                return $pdf->stream('Laporan Absensi Pegawai '.$pegawai->nama_lengkap.'.pdf');
            }
        } else {
            // Export to Excel
            return Excel::download(new PresensiExport($absen, $pegawai, $periode), 'presensi_' . $pegawai->nip . '_' . $periode . '.xlsx');

        }
    }

    public function rekap()
    {
                $tabul = PegawaiModel::get('created_at')->first();
        if (Auth::user()->role == 0) {
            $kantors = KantorModel::all();
        } else if (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $kantors = KantorModel::where('perusahaan', $comp)->get();
        } else if (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $kantors = KantorModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();
            $depts = DeptModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();
        return view('pegawai.rekap', compact('kantors', 'tabul', 'depts'));
        } 

        
        return view('pegawai.rekap', compact('kantors', 'tabul'));
    }

    public function rekapview(Request $request)
    {
        $periode = $request->periode;

        if (Auth::user()->role == 0) {
            $comp = $request->tenant;
            $kantor = $request->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;
        if($satker == null){
             $sat = null;
            $karyawan = PegawaiModel::where('perusahaan', $comp)
                ->where('nama_kantor', $kantor)
                ->where('dept', $dept)
                ->get();

            } else {
                 $sat = SatkerModel::findOrFail($satker);
            $karyawan = PegawaiModel::where('perusahaan', $comp)
                ->where('nama_kantor', $kantor)
                ->where('dept', $dept)
                ->where('satker', $satker)
                ->get();
            }
        } elseif (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $kantor = $request->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;
            if($satker == null){
                 $sat = null;
                $karyawan = PegawaiModel::where('perusahaan', $comp)
                    ->where('nama_kantor', $kantor)
                    ->where('dept', $dept)
                    ->get();

                } else {
                     $sat = SatkerModel::findOrFail($satker);
                $karyawan = PegawaiModel::where('perusahaan', $comp)
                    ->where('nama_kantor', $kantor)
                    ->where('dept', $dept)
                    ->where('satker', $satker)
                    ->get();
                }

        } elseif (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;
            if($satker == null){
                 $sat = null;
                $karyawan = PegawaiModel::where('perusahaan', $comp)
                    ->where('nama_kantor', $kantor)
                    ->where('dept', $dept)
                    ->get();

                } else {
                     $sat = SatkerModel::findOrFail($satker);
                $karyawan = PegawaiModel::where('perusahaan', $comp)
                    ->where('nama_kantor', $kantor)
                    ->where('dept', $dept)
                    ->where('satker', $satker)
                    ->get();
                }
        }


            $inputBulan = $request->periode ?? now()->format('Y-m');
            [$tahun, $bulan] = explode('-', $inputBulan); // parsing "2025-04"

            $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth();

            $rekap = [];
            $depar = DeptModel::findOrFail($dept);
           

            foreach ($karyawan as $k) {
                $absensi = AbsenModel::where('nip', $k->id)
                    ->whereBetween('tgl_absen', [$start, $end])
                    ->get();

                $izin = IzinabsenModel::where('nip', $k->id)
                    ->whereBetween('tanggal', [$start, $end])
                    ->where('status_approve', 1)
                    ->get();

        if($satker != null ){
                $rekap[] = [
                    'nip' => $k->nip,
                    'nama' => $k->nama_lengkap,
                    'absensi' => $absensi,
                    'izin' => $izin,
                ];
            } else {
                $rekap[] = [
                    'nip' => $k->nip,
                    'nama' => $k->nama_lengkap,
                    'sat' => $k->sat->satuan_kerja,
                    'absensi' => $absensi,
                    'izin' => $izin,
                ];
            }
            }

    $agent = new Agent();

    $pdf = Pdf::loadView('pegawai.excelview', compact('rekap', 'bulan', 'tahun', 'periode', 'satker', 'depar', 'sat'))
                  ->setPaper('A4', 'landscape');

            if($request->action == "cetak"){
                // return view('pegawai.excelview', compact('rekap', 'bulan', 'tahun', 'periode', 'satker', 'depar', 'sat'));
                if ($agent->isMobile()){
                    return $pdf->download('Rekap Absensi Pegawai '.Carbon::parse($periode)->isoFormat('MMMM YYYY').'.pdf');
                } else {
                    return $pdf->stream('Rekap Absensi Pegawai '.Carbon::parse($periode)->isoFormat('MMMM YYYY').'.pdf');
                }
            } else {
                return Excel::download(
                    new RekapAbsensiExport($rekap, $bulan, $tahun, $periode, $satker, $depar, $sat, $jumlahHari),
                    'Rekap_Absensi_'.$periode.'.xlsx'
                );
            }
    }

    public function izin()
    {

        if(Auth::user()->role == 0){
        $izinList = IzinabsenModel::paginate(15);
        } else if(Auth::user()->role == 1) {
        $izinList = IzinabsenModel::where('perusahaan', Auth::user()->perusahaan)->paginate(15);
        } else if(Auth::user()->role == 3){
        $izinList = IzinabsenModel::where('perusahaan', Auth::user()->perusahaan)->where('nama_kantor', Auth::user()->kantor)->paginate(15);
        }

        return view('pegawai.izin', compact('izinList'));
    }

    public function izinstatus(Request $request, $id)
    {
        $request->validate([
            'status_approve' => 'required|in:1,2'
        ]);

        $izin = IzinabsenModel::findOrFail($id);
        $izin->status_approve = $request->status_approve;
        $izin->save();

        return response()->json([
            'message' => 'Status izin berhasil diperbarui.'
        ]);
    }

public function delete($id)
{
    try {
        $pegawai = PegawaiModel::findOrFail($id);

        // Hapus file foto jika ada
        if ($pegawai->foto) {
            Storage::delete('public/foto_pegawai/'.$pegawai->nip.'/'.$pegawai->foto);
        }

        $pegawai->delete();

        return response()->json(['message' => 'Pegawai berhasil dihapus.']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Terjadi kesalahan.'], 500);
    }
}

// live view
// public function delete($id)
// {
//     try {
//         $pegawai = PegawaiModel::findOrFail($id);

//         // Hapus file foto jika ada
//         if ($pegawai->foto) {
//             $filePath = base_path('../public_html/foto_pegawai/' . $pegawai->nip . '/' . $pegawai->foto);

//             // Hapus file jika ada
//             if (File::exists($filePath)) {
//                 File::delete($filePath);
//             }

//             // Hapus folder jika kosong
//             $folderPath = base_path('../public_html/foto_pegawai/' . $pegawai->nip);
//             if (File::isDirectory($folderPath) && count(File::files($folderPath)) === 0) {
//                 File::deleteDirectory($folderPath);
//             }
//         }

//         // Hapus data pegawai dari database
//         $pegawai->delete();

//         return response()->json(['message' => 'Pegawai berhasil dihapus.']);
//     } catch (\Exception $e) {
//         return response()->json(['message' => 'Terjadi kesalahan.'], 500);
//     }
// }

public function lembur()
    {
    if(Auth::user()->role == 0){
        $lembur = LemburModel::latest()->paginate(15);
        } else if(Auth::user()->role == 1) {
        $lembur = LemburModel::where('perusahaan', Auth::user()->perusahaan)->latest()->paginate(15);
        } else if(Auth::user()->role == 3){
        $lembur = LemburModel::where('perusahaan', Auth::user()->perusahaan)->where('kantor', Auth::user()->kantor)->latest()->paginate(15);
        }

        return view('pegawai.lembur', compact('lembur'));
    }

    public function aprv_adm(Request $request, $id)
    {
        $request->validate([
            'aprv_by_adm' => 'required'
        ]);

        $apprv = LemburModel::findOrFail($id);
        $apprv->aprv_by_adm = $request->aprv_by_adm;
        $apprv->save();

        return response()->json([
            'message' => 'Status lembur berhasil diperbarui.'
        ]);
    }

public function laplem()
{
    if (Auth::user()->role == 0) {
            $karyawans = PegawaiModel::all();
            $kantors = KantorModel::all();
            $tabul = PegawaiModel::get('created_at')->first();

        return view('pegawai.laplem', compact('karyawans', 'kantors', 'tabul'));
        } elseif (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $karyawans = PegawaiModel::where('perusahaan', $comp)->get();
            $kantors = KantorModel::where('perusahaan', $comp)->get();
            $tabul = PegawaiModel::get('created_at')->first();

        return view('pegawai.laplem', compact('karyawans', 'kantors', 'tabul'));
        } elseif (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $tabul = PegawaiModel::get('created_at')->first();
            $karyawans = PegawaiModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();
            $depts = DeptModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();

        return view('pegawai.laplem', compact('karyawans', 'tabul', 'depts'));
        } 
}

    public function prelem(Request $request)
    {
        $periode = $request->periode;
        $id = $request->pegawais;

        $pegawai = PegawaiModel::findOrFail($id);

        $lembur = LemburModel::with('pegawai')
            ->where('nip', $pegawai->id)
            ->where('tgl_absen', 'LIKE', '%' . $periode . '%')
            ->whereNotNull('jam_in')
            ->whereNotNull('jam_out')
            ->orderBy('tgl_absen')
            ->get();

    $agent = new Agent();

    $pdf = Pdf::loadView('pegawai.prevlem', compact('pegawai', 'lembur', 'periode'))
                  ->setPaper('A4', 'portrait');

        if ($request->action == "cetak") {
            if ($agent->isMobile()){
                return $pdf->download('Laporan Absensi Pegawai '.$pegawai->nama_lengkap.'.pdf');
            } else {
                return $pdf->stream('Laporan Absensi Pegawai '.$pegawai->nama_lengkap.'.pdf');
            }
        } else {
            // Export to Excel
            return Excel::download(new LemburExport($lembur, $pegawai, $periode), 'lembur_' . $pegawai->nip . '_' . $periode . '.xlsx');

        }
    }

    public function rekaplembur()
    {
        $tabul = PegawaiModel::get('created_at')->first();

        if (Auth::user()->role == 0) {
            $kantors = KantorModel::all();
        } else if (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $kantors = KantorModel::where('perusahaan', $comp)->get();
        } else if (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $kantors = KantorModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();
            $depts = DeptModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();
        return view('pegawai.rekaplembur', compact('kantors', 'tabul', 'depts'));
        } 
        return view('pegawai.rekaplembur', compact('kantors', 'tabul'));
    }

    public function reklem(Request $request)
    {
        $periode = $request->periode;

    if (Auth::user()->role == 0) {
            $comp = $request->tenant;
            $kantor = $request->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;
        if($satker == null){
             $sat = null;
            $pegawaiList = PegawaiModel::where('perusahaan', $comp)
                ->where('nama_kantor', $kantor)
                ->where('dept', $dept)
                ->get();

            } else {
                 $sat = SatkerModel::findOrFail($satker);
            $pegawaiList = PegawaiModel::where('perusahaan', $comp)
                ->where('nama_kantor', $kantor)
                ->where('dept', $dept)
                ->where('satker', $satker)
                ->get();
            }
    } elseif (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $kantor = $request->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;
            if($satker == null){
                 $sat = null;
                $pegawaiList = PegawaiModel::where('perusahaan', $comp)
                    ->where('nama_kantor', $kantor)
                    ->where('dept', $dept)
                    ->get();

                } else {
                     $sat = SatkerModel::findOrFail($satker);
                $pegawaiList = PegawaiModel::where('perusahaan', $comp)
                    ->where('nama_kantor', $kantor)
                    ->where('dept', $dept)
                    ->where('satker', $satker)
                    ->get();
                }

    } elseif (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;
            if($satker == null){
                 $sat = null;
                $pegawaiList = PegawaiModel::where('perusahaan', $comp)
                    ->where('nama_kantor', $kantor)
                    ->where('dept', $dept)
                    ->get();

                } else {
                     $sat = SatkerModel::findOrFail($satker);
                $pegawaiList = PegawaiModel::where('perusahaan', $comp)
                    ->where('nama_kantor', $kantor)
                    ->where('dept', $dept)
                    ->where('satker', $satker)
                    ->get();
            }
    }

            $inputBulan = $request->periode ?? now()->format('Y-m');
            [$tahun, $bulan] = explode('-', $inputBulan); // parsing "2025-04"

            $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth();

        $rekap = [];
        $depar = DeptModel::where('id', $dept)->first();

        foreach ($pegawaiList as $pegawai) {
            $lembur = LemburModel::where('nip', $pegawai->id)
                ->whereMonth('tgl_absen', $bulan)
                ->whereYear('tgl_absen', $tahun)
                ->orderBy('satker', 'asc')
                ->get();

            $rekap[] = [
                'nip' => $pegawai->nip,
                'nama' => $pegawai->nama_lengkap,
                'sat' => $pegawai->sat->satuan_kerja ?? '-', // pastikan relasi satker ada
                'lembur' => $lembur,
            ];
        }

        // return view('pegawai.reklem', [
        //     'rekap' => $rekap,
        //     'bulan' => $bulan,
        //     'tahun' => $tahun,
        //     'satker' => null // atau isi sesuai filter user
        // ]);



            $agent = new Agent();

    $pdf = Pdf::loadView('pegawai.reklem', compact('rekap', 'bulan', 'tahun', 'periode', 'satker', 'depar', 'sat'))
                  ->setPaper('A4', 'landscape');

            if($request->action == "cetak"){
                // return view('pegawai.excelview', compact('rekap', 'bulan', 'tahun', 'periode', 'satker', 'depar', 'sat'));
                if ($agent->isMobile()){
                    return $pdf->download('Rekap_Lembur_'.Carbon::parse($periode)->isoFormat('MMMM_YYYY').'.pdf');
                } else {
                    return $pdf->stream('Rekap_Lembur_'.Carbon::parse($periode)->isoFormat('MMMM_YYYY').'.pdf');
                }
            } else {
                return Excel::download(
                    new LemburBulananExport($rekap, $bulan, $tahun, $periode, $satker, $depar, $sat, $jumlahHari),
                    'Rekap_Lembur_'.Carbon::parse($periode)->isoFormat('MMMM_YYYY').'.xlsx'
                );
            }
    }
}
