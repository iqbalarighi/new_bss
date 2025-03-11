<?php

namespace App\Http\Controllers;

use App\Models\JabatanModel;
use App\Models\KantorModel;
use App\Models\PegawaiModel;
use App\Models\PerusahaanModel;
use App\Models\SatkerModel;
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
    } else {
        $pegawais = PegawaiModel::with('perusa', 'kantor', 'jabat', 'sat')
        ->where('perusahaan', Auth::user()->perusahaan)
        ->paginate(15);

    return view('pegawai.index', compact('pegawais'));
    }

        
    }

    public function input()
    {
        $id = Auth::user()->perusahaan;

        $tenant = PerusahaanModel::get();
        $kantor = KantorModel::where('perusahaan', $id)->get();
        $jabatan = JabatanModel::where('perusahaan', $id)->get();
        $satker = SatkerModel::get();

        return view('pegawai.input', compact('tenant', 'kantor', 'jabatan', 'satker'));
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
            'bpjs_tk' => 'required|string',
            'bpjs_kesehatan' => 'required|string',
            'kontak_darurat' => 'required|string|max:15',
            'penempatan_kerja' => 'required|string',
            'satker' => 'required|string',
            'status' => 'required|string|in:Aktif,Tidak Aktif',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
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
            'bpjs_tk.required' => 'BPJS TK wajib diisi.',
            'bpjs_kesehatan.required' => 'BPJS Kesehatan wajib diisi.',
            'kontak_darurat.required' => 'Kontak darurat wajib diisi.',
            'penempatan_kerja.required' => 'Penempatan kerja wajib diisi.',
            'satker.required' => 'Satker wajib diisi.',
            'status.required' => 'Status pegawai wajib diisi.',
            'foto.required' => 'Foto wajib diunggah.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $foto = $request->file('foto');
        $fotoNama = Str::random(20) . '.' . $foto->getClientOriginalExtension();
        $fotoPath = $foto->storeAs('foto_pegawai', $fotoNama, 'public');

        $id = Auth::user()->perusahaan;

        PegawaiModel::create([
            'perusahaan' => $id,
            'nama_lengkap' => $request->nama,
            'nip' => $request->nip,
            'password' => Hash::make($request->password),
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'domisili' => $request->alamat_domisili,
            'no_hp' => $request->no_telepon,
            'jabatan' => $request->jabatan,
            'bpjs_tk' => $request->bpjs_tk,
            'bpjs_sehat' => $request->bpjs_kesehatan,
            'ko_drat' => $request->kontak_darurat,
            'nama_kantor' => $request->penempatan_kerja,
            'satker' => $request->satker,
            'status' => $request->status,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }
}
