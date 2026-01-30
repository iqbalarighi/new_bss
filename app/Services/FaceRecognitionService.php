<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class FaceRecognitionService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.face_api.url');
    }

    public function registerFace(UploadedFile $image): array
    {
        $response = Http::timeout(30)
            ->attach(
                'image',
                fopen($image->getRealPath(), 'r'),
                $image->getClientOriginalName()
            )
            ->post($this->baseUrl . '/register');

        if (!$response->successful()) {
            throw new \Exception(
                $response->json('message') ?? 'Face API error'
            );
        }

        return $response->json();
    }


    public function verifyFace(UploadedFile $image, array $storedEmbedding): array
        {
            $response = Http::timeout(30)
                ->attach(
                    'image',
                    fopen($image->getRealPath(), 'r'),
                    $image->getClientOriginalName()
                )
                ->post($this->baseUrl.'/verify', [
                    'embedding' => json_encode($storedEmbedding)
                ]);

            if (!$response->successful()) {
                throw new \Exception(
                    $response->json('message') ?? 'Verifikasi wajah gagal'
                );
            }

            return $response->json();
        }

        private function sendImage(string $endpoint, UploadedFile $image): array
        {
            $response = Http::timeout(30)
                ->attach(
                    'image',
                    fopen($image->getRealPath(), 'r'),
                    $image->getClientOriginalName()
                )
                ->post($this->baseUrl.$endpoint);

            if (!$response->successful()) {
                throw new \Exception(
                    $response->json('message') ?? 'Face API error'
                );
            }

            return $response->json();
        }
}
