<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = PegawaiModel::paginate(15);

        return view('pegawai.index', compact('pegawais'));
    }

    public function input()
    {
        return view('pegawai.input');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:pegawais',
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
            'status_pegawai' => 'required|string|in:Aktif,Tidak Aktif',
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
            'status_pegawai.required' => 'Status pegawai wajib diisi.',
            'foto.required' => 'Foto wajib diunggah.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $foto = $request->file('foto');
        $fotoNama = Str::random(20) . '.' . $foto->getClientOriginalExtension();
        $fotoPath = $foto->storeAs('foto_pegawai', $fotoNama, 'public');

        Pegawai::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'password' => Hash::make($request->password),
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'alamat_domisili' => $request->alamat_domisili,
            'no_telepon' => $request->no_telepon,
            'jabatan' => $request->jabatan,
            'bpjs_tk' => $request->bpjs_tk,
            'bpjs_kesehatan' => $request->bpjs_kesehatan,
            'kontak_darurat' => $request->kontak_darurat,
            'penempatan_kerja' => $request->penempatan_kerja,
            'satker' => $request->satker,
            'status_pegawai' => $request->status_pegawai,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }
}
