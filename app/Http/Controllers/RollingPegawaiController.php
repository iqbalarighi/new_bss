<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PegawaiModel;
use App\Models\PegawaiRollingModel;
use App\Models\KantorModel;
use App\Models\DeptModel;
use App\Models\SatkerModel;
use App\Models\JabatanModel;
use App\Models\ShiftModel;
use Illuminate\Support\Facades\Auth;

class RollingPegawaiController extends Controller
{
    public function index()
    {
        $data = PegawaiRollingModel::with('pegawai')->latest()->paginate(10);
        return view('master.rollingpegawai', compact('data'));
    }

    public function create()
    {
        return view('master.rollingcreate');
    }

public function store(Request $request)
{
    $request->validate([
        'pegawai_id' => 'required',
        'tanggal_efektif' => 'required|date'
    ]);

    PegawaiRollingModel::create([
        'pegawai_id' => $request->pegawai_id,
        'perusahaan' => auth()->user()->perusahaan, // 🔥 override disini
        'kantor' => $request->kantor,
        'dept' => $request->dept,
        'satker' => $request->satker,
        'jabatan' => $request->jabatan,
        'tanggal_efektif' => $request->tanggal_efektif,
    ]);

    return redirect()->route('rollingpegawai.index')
        ->with('success', 'Schedule rolling berhasil dibuat');
}

    // API DETAIL PEGAWAI (AUTO FILL)
    public function detail($id)
    {
        $pegawai = PegawaiModel::with([
            'kantor','deptmn','sat','jabat'
        ])->findOrFail($id);

        return response()->json($pegawai);
    }

    public function update(Request $request, $id)
    {
        $data = PegawaiRollingModel::findOrFail($id);

        $data->update([
            'pegawai_id' => $request->pegawai_id,
            'kantor' => $request->kantor,
            'dept' => $request->dept,
            'satker' => $request->satker,
            'jabatan' => $request->jabatan,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        PegawaiRollingModel::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}