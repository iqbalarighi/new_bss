<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\TukarJaga;
use App\Models\TukarJagaBarang;
use App\Models\KantorModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TukarJagaController extends Controller
{
    public function index()
    {
        $user = Auth::guard('pegawai')->user();

        $serah = TukarJaga::with('barang')
            ->where('perusahaan_id', $user->perusahaan)
            ->where('kantor_id', $user->nama_kantor)
            ->where('user_id', $user->id)
            ->latest()
            ->get();

            $last = TukarJaga::where('user_id', $user->id)
                ->where('kantor_id', $user->nama_kantor)
                ->orderBy('created_at', 'desc')
                ->first();

        if ($last) {
             if (Carbon::parse($last->created_at)->isSameDay(now())) {

                $selisihJam = now()->diffInHours($last->created_at);

                } else {
                $selisihJam = null;
            }
            } else {
                $selisihJam = null;
            }
        

        return view('absen.viewserah', compact('serah', 'selisihJam'));
    }

    public function show($id)
    {
        $detail = TukarJaga::with('barang')->findOrFail($id);

        return view('absen.detilserah', compact('detail'));
    }

    public function create()
    {
        $user = Auth::guard('pegawai')->user();

        $kantor = KantorModel::where('perusahaan', $user->perusahaan)
            ->where('id', $user->nama_kantor)
            ->first();

        return view('absen.createkarga', compact('kantor','user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'        => 'required|date',
            'shift'          => 'required|in:Pagi,Malam',
            'petugas_lama'   => 'required|string',
            'petugas_baru'   => 'required|string',
            'kejadian'       => 'nullable|string',

            'nama_barang'    => 'nullable|array',
            'nama_barang.*'  => 'nullable|string',

            'jumlah'         => 'nullable|array',
            'jumlah.*'       => 'nullable|integer',

            'kondisi'        => 'nullable|array',
            'kondisi.*'      => 'nullable|string',
        ]);

            // user pegawai login
            $user = Auth::guard('pegawai')->user();

            // laporan terakhir user (shift & kantor sama)
            $last = TukarJaga::where('user_id', $user->id)
                ->where('kantor_id', $user->nama_kantor)
                ->where('shift', $request->shift)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last) {

                // cek hanya jika masih hari yang sama
                if (Carbon::parse($last->created_at)->isSameDay(now())) {

                    $selisihJam = now()->diffInHours($last->created_at);

                    if ($selisihJam < 5) {
                        return redirect()
                            ->route('tukar-jaga.index')
                            ->with('error', 'Anda baru saja membuat laporan.');
                    }

                }
                // jika beda hari → otomatis lolos (tidak dicek jam)
            }


        DB::beginTransaction();

        try {


            $kantor = KantorModel::where('id', $user->nama_kantor)->first();

            $tukarJaga = TukarJaga::create([
                'user_id'        => $user->id,
                'perusahaan_id'  => $user->perusahaan,
                'kantor_id'      => $user->nama_kantor,
                'tanggal'        => $request->tanggal,
                'shift'          => $request->shift,
                'lokasi_gedung'  => $kantor->nama_kantor ?? '-',
                'no_lap'         => TukarJaga::generateNoLap(),
                'petugas_lama'   => $request->petugas_lama,
                'petugas_baru'   => $request->petugas_baru,
                'kejadian'       => $request->kejadian,
            ]);

            if ($request->nama_barang) {

                foreach ($request->nama_barang as $i => $nama) {

                    if (!$nama) continue;

                    TukarJagaBarang::create([
                        'tukar_jaga_id' => $tukarJaga->id,
                        'nama_barang'   => $nama,
                        'jumlah'        => $request->jumlah[$i] ?? 0,
                        'kondisi'       => $request->kondisi[$i] ?? '-',
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('tukar-jaga.index')
                ->with('success', 'Serah Terima Jaga berhasil disimpan');

        } catch (\Throwable $e) {

            DB::rollBack();

            // sementara debug dulu
            // dd($e->getMessage());
            return back()
        ->with('error', 'Gagal menyimpan data. Silakan coba lagi.');

        }
    }

    public function edit($id)
    {
        $detail = TukarJaga::with('barang')->findOrFail($id);

        return view('absen.editserah', compact('detail'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'tanggal'        => 'required|date',
        'shift'          => 'required|in:Pagi,Malam',
        'petugas_lama'   => 'required|string',
        'petugas_baru'   => 'required|string',
        'kejadian'       => 'nullable|string',

        // barang
        'nama_barang.*'  => 'nullable|string',
        'jumlah.*'       => 'nullable|integer',
        'kondisi.*'      => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {

        $tukarJaga = TukarJaga::findOrFail($id);

        // Update header
        $tukarJaga->update([
            'tanggal'      => $request->tanggal,
            'shift'        => $request->shift,
            'petugas_lama' => $request->petugas_lama,
            'petugas_baru' => $request->petugas_baru,
            'kejadian'     => $request->kejadian,
        ]);

        // ====== hapus barang lama lalu insert ulang ======
        TukarJagaBarang::where('tukar_jaga_id', $tukarJaga->id)->delete();

        if ($request->nama_barang) {
            foreach ($request->nama_barang as $i => $nama) {

                if (!$nama) continue;

                TukarJagaBarang::create([
                    'tukar_jaga_id' => $tukarJaga->id,
                    'nama_barang'   => $nama,
                    'jumlah'        => $request->jumlah[$i] ?? 0,
                    'kondisi'       => $request->kondisi[$i] ?? '-',
                ]);
            }
        }

        DB::commit();

        return redirect()
            ->route('tukar-jaga.show', $tukarJaga->id)
            ->with('success', 'Data berhasil diperbarui');

    } catch (\Throwable $e) {

        DB::rollBack();
        // return back()
        //     ->with('error', 'Gagal update: ' . $e->getMessage());
        dd($e->getMessage());
    }
}

public function downloadPdf($id)
    {
        $header = TukarJaga::with('karyawan')->findOrFail($id);

        $barang = TukarJagaBarang::where('tukar_jaga_id', $id)->get();

        $kantor = KantorModel::find($header->karyawan->nama_kantor);

        $pdf = Pdf::loadView('absen.pdfserah', [
            'header' => $header,
            'barang' => $barang,
            'kantor' => $kantor
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Serah Terima Jaga '.$header->no_lap.'.pdf');
    }

}
