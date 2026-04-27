<?php

namespace App\Services;

use App\Models\AbsenModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FaceFallbackVerifier
{
    public function verify(
        AbsenModel $absen,
        FaceRecognitionService $faceService
    ): void {

        /**
         * Batasi hanya 2 hari terakhir
         */
        if (
            Carbon::parse($absen->tgl_absen)
                ->lt(now()->subDays(2))
        ) {
            return;
        }

        /**
         * Ambil pegawai
         */
        $pegawai = $absen->pegawai;

        if (!$pegawai || !$pegawai->face_embedding) {
            return;
        }

        /**
         * Ambil foto absensi
         */
        if (!$absen->foto_in) {
            return;
        }

        $imagePath = storage_path(
            'app/public/absensi/' . $pegawai->nip . '/' . $absen->foto_in
        );

        if (!file_exists($imagePath)) {
            return;
        }

        try {
            /**
             * 🔥 PENTING: kirim sebagai UploadedFile
             */
            $uploadedFile = new UploadedFile(
                $imagePath,
                basename($imagePath),
                mime_content_type($imagePath),
                null,
                true // ← penting! bypass is_uploaded_file()
            );

            
            $embedding = $pegawai->face_embedding;

            if (is_string($embedding)) {
                $embedding = json_decode($embedding, true);
            }

            if (!is_array($embedding) || empty($embedding)) {
                return;
            }

            $verify = $faceService->verifyFace(
                $uploadedFile,
                $embedding
            );
            
            $data = $verify['data'] ?? [];

            /**
             * ===== WAJAH TIDAK TERDETEKSI =====
             * Python tidak mengembalikan "match"
             */
            if (!array_key_exists('match', $data)) {

                $absen->update([
                    'face_status'   => 'no_face',
                    'face_verified' => false,
                    'face_score'    => null,
                    'verified_at'   => null,
                ]);

                return;
            }

            /**
             * ===== WAJAH ADA TAPI TIDAK MATCH =====
             */
            if ($data['match'] === false) {

                $absen->update([
                    'face_status'   => 'failed',
                    'face_verified' => false,
                    'face_score'    => $data['similarity'] ?? null,
                    'verified_at'   => null,
                ]);

                return;
            }

            /**
             * ===== VERIFIED =====
             */
            $absen->update([
                'face_status'   => 'verified',
                'face_verified' => true,
                'face_score'    => $data['similarity'],
                'is_fallback'   => false,
                'verified_at'   => now(),
            ]);


        } catch (\Throwable $e) {

            /**
             * Face API masih mati
             */
            Log::info('Face fallback still pending', [
                'absen_id' => $absen->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }
}
