<?php

namespace App\Http\Controllers;

use App\Models\AbsenModel;
use App\Models\DeptModel;
use App\Models\IzinabsenModel;
use App\Models\JabatanModel;
use App\Models\KantorModel;
use App\Models\PegawaiModel;
use App\Models\PerusahaanModel;
use App\Models\SatkerModel;
use App\Models\ShiftModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        if(Auth::user()->role === 0){
            $tenant = PerusahaanModel::get();
            $kantorList = KantorModel::get();
            $jabatanList = JabatanModel::get();
            $satkerList = SatkerModel::get();
            $departemenList = DeptModel::get();
            $shift = ShiftModel::get();
        } else {
            $kantorList = KantorModel::where('perusahaan', $pegawai->perusahaan)->where('id', $pegawai->nama_kantor)->get();
            $departemenList = DeptModel::where('perusahaan', $pegawai->perusahaan)->where('nama_kantor', $pegawai->nama_kantor)->get();
            $satkerList = SatkerModel::where('perusahaan', $pegawai->perusahaan)->where('kantor', $pegawai->nama_kantor)->where('dept_id', $pegawai->dept)->get();
            $jabatanList = JabatanModel::where('perusahaan', $pegawai->perusahaan)->where('kantor_id', $pegawai->nama_kantor)->where('dept_id', $pegawai->dept)->where('satker_id', $pegawai->satker)->get();
            $shift = ShiftModel::where('satker_id', $pegawai->satker)->get();
        }


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
            'shift' => 'required|string',
            'bpjs_tk' => 'required|string',
            'bpjs_kesehatan' => 'required|string',
            'kontak_darurat' => 'required|string|max:15',
            'satker' => 'required|string',
            'status' => 'required|string|in:Aktif,Tidak Aktif',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
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
            'shift.required' => 'Shift wajib diisi.',
            'bpjs_tk.required' => 'BPJS TK wajib diisi.',
            'bpjs_kesehatan.required' => 'BPJS Kesehatan wajib diisi.',
            'kontak_darurat.required' => 'Kontak darurat wajib diisi.',
            'satker.required' => 'Satker wajib diisi.',
            'status.required' => 'Status pegawai wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
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

if($foto != null){
        $fotoNama = Str::random(20) . '.' . $foto->getClientOriginalExtension();
        $fotoPath = $foto->storeAs('foto_pegawai/'.$request->nip, $fotoNama, 'public');
} else {
    $fotoNama = null;
}
        PegawaiModel::create([
            'perusahaan' => $id,
            'nama_lengkap' => $request->nama,
            'nip' => $request->nip,
            'dept' => $request->dept,
	        'shift' => $request->shift,
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
        'shift' => 'required|string',
        'bpjs_tk' => 'required|string',
        'bpjs_kesehatan' => 'required|string',
        'kontak_darurat' => 'required|string|max:15',
        'satker' => 'required|string',
        'status' => 'required|string|in:Aktif,Tidak Aktif',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
        $fotoPath = $foto->storeAs('foto_pegawai/' . $request->nip, $fotoNama, 'public');
    } else {
        $fotoNama = $pegawai->foto; // Pakai yang lama
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


    public function absensi(Request $request)
    {
        
        if (Auth::user()->role == 0) {
            $absen = AbsenModel::latest()->paginate(10);
        } elseif (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;

                $absen = AbsenModel::where('perusahaan', $comp)
                    ->latest()
                    ->paginate(10); // Sesuaikan kolomnya

        } elseif (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;

                $absen = AbsenModel::where('perusahaan', $comp)
                    ->where('kantor', $kantor)
                    ->latest()
                    ->paginate(10); // Sesuaikan kolomnya

        } 

        return view('pegawai.absensi', compact('absen'));
    }

    public function getAbs(Request $request)
    {
        $bultah = $request->bultah; // Format: YYYY-MM

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

        return view('pegawai.getabsensi', compact('absen'));
    }

    public function lapor()
    {
        if (Auth::user()->role == 0) {
            $karyawans = PegawaiModel::all();

        return view('pegawai.laporan', compact('karyawans', 'kantors', 'departemens'));
        } elseif (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $karyawans = PegawaiModel::where('perusahaan', $comp)->get();
            $kantors = KantorModel::where('perusahaan', $comp)->get();
            $tabul = PegawaiModel::get('created_at')->first();

        return view('pegawai.laporan', compact('karyawans', 'kantors', 'tabul'));
        } elseif (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
        $karyawans = PegawaiModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();
        return view('pegawai.laporan', compact('karyawans', 'kantors'));
        } 

    }

    public function preview(Request $request)
    {

        $periode = $request->periode;

        $orng = $request->pegawais;
        $pegawai = PegawaiModel::findOrFail($orng);
        $absen = AbsenModel::where('nip', $pegawai->id)
        ->where('tgl_absen', 'LIKE', '%'.$periode.'%')
        ->get();

    return view('pegawai.preview', compact('pegawai', 'absen', 'periode'));

    }

    public function rekap()
    {
        if (Auth::user()->role == 0) {
            $kantors = KantorModel::all();
        } else if (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $kantors = KantorModel::where('perusahaan', $comp)->get();
        } else if (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $kantors = KantorModel::where('perusahaan', $comp)->where('nama_kantor', $kantor)->get();
        } 

        
        return view('pegawai.rekap', compact('kantors'));
    }

    public function rekapview(Request $request)
    {
        $periode = $request->periode;

        if (Auth::user()->role == 0) {
            $comp = $request->perusahaan;
            $kantor = $request->kantor;
        } elseif (Auth::user()->role == 1) {
            $comp = Auth::user()->perusahaan;
            $kantor = $request->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;

            $karyawan = PegawaiModel::where('perusahaan', $comp)
            ->where('nama_kantor', $kantor)
            ->where('dept', $dept)
            ->where('satker', $satker)
            ->get();
        } elseif (Auth::user()->role == 3) {
            $comp = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->departemen;

            $karyawan = PegawaiModel::where('perusahaan', $comp)
            ->where('nama_kantor', $kantor)
            ->where('dept', $dept)
            ->where('satker', $satker)
            ->get();
        }
            $inputBulan = $request->periode ?? now()->format('Y-m');
            [$tahun, $bulan] = explode('-', $inputBulan); // parsing "2025-04"

            $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            
            $rekap = [];

            foreach ($karyawan as $k) {
                $absensi = AbsenModel::where('nip', $k->id)
                    ->whereBetween('tgl_absen', [$start, $end])
                    ->get();

                $izin = IzinabsenModel::where('nip', $k->id)
                    ->whereBetween('tanggal', [$start, $end])
                    ->where('status_approve', 1)
                    ->get();

                $rekap[] = [
                    'nip' => $k->nip,
                    'nama' => $k->nama_lengkap,
                    'absensi' => $absensi,
                    'izin' => $izin,
                ];
            }

            return view('pegawai.excel', compact('rekap', 'bulan', 'tahun', 'periode'));
    }

    public function izin()
    {
        return view('pegawai.izin');
    }
}
