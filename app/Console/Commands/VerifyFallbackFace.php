<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AbsenModel;
use App\Services\FaceFallbackVerifier;
use App\Services\FaceRecognitionService;

class VerifyFallbackFace extends Command
{
    protected $signature = 'face:verify-fallback';
    protected $description = 'Verifikasi ulang absensi fallback wajah';

    public function handle()
    {
        $fallbackVerifier = app(FaceFallbackVerifier::class);
        $faceService      = app(FaceRecognitionService::class);

        $absensi = AbsenModel::where('is_fallback', true)
            ->whereNull('verified_at')
            ->whereDate('tgl_absen', '>=', now()->subDays(3))
            ->get();

        foreach ($absensi as $absen) {
            $fallbackVerifier->verify($absen, $faceService);
        }

        $this->info('Fallback face verification completed.');
    }
}
