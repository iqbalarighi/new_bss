<?php

// app/Http/Controllers/FaceRecognitionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PegawaiModel;
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
            $result = $face->registerFace($request->file('image'));

            $pegawai->update([
                'face_embedding' => json_encode($result['embedding'])
            ]);

            return response()->json([
                'message' => 'Registrasi wajah berhasil'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
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
