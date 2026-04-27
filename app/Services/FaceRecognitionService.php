<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class FaceRecognitionService
{
    /**
     * Daftar server Face API
     * urutan = prioritas
     */
    protected array $servers;

    public function __construct()
    {
        $this->servers = [
            config('services.face_api.primary'),
            config('services.face_api.backup'),
        ];
    }

    /* =====================================================
     | REGISTER FACE
     ===================================================== */
    public function registerFace(UploadedFile $image): array
    {
        foreach ($this->servers as $server) {
            try {
                $response = Http::timeout(10)
                    ->attach(
                        'image',
                        fopen($image->getRealPath(), 'r'),
                        $image->getClientOriginalName()
                    )
                    ->post($server . '/register');

                $status = $response->status();
            $body   = $response->json();

            /**
             * ✅ HASIL VALID (APAPUN MATCH / FAIL)
             * → JANGAN PINDAH SERVER
             */
            if (in_array($status, [200, 403, 422])) {
                return [
                    'server' => $server,
                    'status' => $status,
                    'data'   => $body
                ];
            }

            /**
             * 🔥 ERROR SERVER
             * → COBA SERVER BERIKUTNYA
             */
            if ($status >= 500) {
                continue;
            }

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // 🔥 timeout / server mati
                continue;
            }
        }

        throw new \Exception('Semua Face API tidak dapat diakses');
    }

    /* =====================================================
     | VERIFY FACE
     | return null => semua server mati (fallback)
     ===================================================== */
public function verifyFace(
    UploadedFile $image,
    array $storedEmbedding
): array {

    foreach ($this->servers as $server) {
        try {
            $response = Http::timeout(10)
                ->attach(
                    'image',
                    fopen($image->getRealPath(), 'r'),
                    $image->getClientOriginalName()
                )
                ->post($server . '/verify', [
                    'embedding' => json_encode($storedEmbedding)
                ]);

            $status = $response->status();
            $body   = $response->json();

            /**
             * ✅ HASIL VALID (APAPUN MATCH / FAIL)
             * → JANGAN PINDAH SERVER
             */
            if (in_array($status, [200, 403, 422])) {
                return [
                    'server' => $server,
                    'status' => $status,
                    'data'   => $body
                ];
            }

            /**
             * 🔥 ERROR SERVER
             * → COBA SERVER BERIKUTNYA
             */
            if ($status >= 500) {
                continue;
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // 🔥 timeout / server mati
            continue;
        }
    }

    /**
     * 🔥 SEMUA SERVER GAGAL TEKNIS
     */
    throw new \RuntimeException('Face recognition service unavailable');
}


}
