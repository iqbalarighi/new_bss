<?php

// app/Http/Controllers/FaceRecognitionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PegawaiModel;
use App\Models\AbsenModel;
use App\Services\FaceRecognitionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class FaceRecognitionController extends Controller
{
    protected FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    // 🔹 REGISTRASI WAJAH PEGAWAI
public function register(Request $request, FaceRecognitionService $face)
    {
        $request->validate([
            'image' => 'required|file|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $pegawai = auth()->guard('pegawai')->user();

        if (!$pegawai) {
            return response()->json([
                'message' => 'Pegawai tidak terautentikasi'
            ], 401);
        }

        try {
            /**
             * =============================
             * REGISTER KE FACE API
             * =============================
             */
            $result = $face->registerFace($request->file('image'));

            $status = $result['status'] ?? 500;
            $data   = $result['data']   ?? [];

            /**
             * ❌ VALIDATION ERROR DARI FACE API
             * (no_face, multiple_face, dll)
             */
            if ($status !== 200) {
                return response()->json([
                    'message' => 'Registrasi wajah gagal',
                    'reason'  => $data['reason'] ?? 'unknown'
                ], $status);
            }

            /**
             * ❌ EMBEDDING TIDAK ADA (SAFETY)
             */
            if (!isset($data['embedding']) || !is_array($data['embedding'])) {
                return response()->json([
                    'message' => 'Embedding wajah tidak diterima'
                ], 500);
            }

            /**
             * =============================
             * SIMPAN EMBEDDING PEGAWAI
             * =============================
             */

            /**
             * =============================
             * SET FALLBACK UNTUK ABSENSI
             * - hari ini & kemarin
             * - belum absen pulang
             * =============================
             */
            $nip_id    = Auth::guard('pegawai')->user()->id;
            $tgl_absen = date('Y-m-d');
            
            $absenSebelumnya = AbsenModel::where('nip', $nip_id)
            ->where('tgl_absen', '<', $tgl_absen)
            ->whereNull('jam_out')
            ->latest('tgl_absen')
            ->first();
            
            if ($absenSebelumnya) {
                $absenSebelumnya->update([
                    'is_fallback' => 1,
                ]);
            }

            $cek = AbsenModel::where('nip', $nip_id)
            ->where('tgl_absen', $tgl_absen)
            ->first();
            
            if ($cek) {
                $cek->update([
                    'is_fallback' => 1,
                ]);
            }

            $pegawai->update([
                'face_embedding' => $data['embedding'], // sudah cast array
            ]);


            return response()->json([
                'message' => 'Registrasi wajah berhasil'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Face recognition service unavailable'
            ], 503);
        }
    }


public function delete(Request $request, $id)
    {

        $pegawai = PegawaiModel::find($id);

        if (!$pegawai || !$pegawai->face_embedding) {
            return response()->json([
                'message' => 'Data wajah tidak ditemukan'
            ], 404);
        }

        // Hapus embedding
        $pegawai->update([
            'face_embedding' => null
        ]);

        return response()->json([
            'message' => 'Data wajah berhasil dihapus'
        ]);
    }

}
