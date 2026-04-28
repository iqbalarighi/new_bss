<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\PengecualianAbsen;
use Illuminate\Support\Facades\DB;

class CleanPengecualian extends Command
{
    protected $signature = 'app:clean-pengecualian';
    protected $description = 'Membersihkan tanggal pengecualian yang sudah lewat';

    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
    
        PengecualianAbsen::select('id', 'tanggal')
            ->chunkById(200, function ($rows) use ($today) {
    
                foreach ($rows as $item) {
    
                    // =========================
                    // 🔥 JIKA NULL → SKIP
                    // =========================
                    if (is_null($item->tanggal)) {
                        continue;
                    }
    
                    // =========================
                    // 🔥 NORMALISASI DATA
                    // =========================
                    $tanggalList = is_array($item->tanggal)
                        ? $item->tanggal
                        : explode(',', $item->tanggal);
    
                    $tanggalList = array_values(array_filter(array_map('trim', $tanggalList)));
    
                    $tanggalBaru = [];
    
                    foreach ($tanggalList as $tgl) {
                        if ($tgl >= $today) {
                            $tanggalBaru[] = $tgl;
                        }
                    }
    
                    // =========================
                    // 🔥 RULE BARU
                    // =========================
    
                    // ❌ jika sisa ≤ 1 → HAPUS
                    if (count($tanggalBaru) <= 1) {
                        DB::table('pengecualian_absen')
                            ->where('id', $item->id)
                            ->delete();
                        continue;
                    }
    
                    // ⏭️ jika tidak berubah → skip
                    if ($tanggalBaru === $tanggalList) {
                        continue;
                    }
    
                    // ✅ update jika masih > 1
                    DB::table('pengecualian_absen')
                        ->where('id', $item->id)
                        ->update([
                            'tanggal' => json_encode(array_values($tanggalBaru))
                        ]);
                }
            });
    
        $this->info('Pembersihan pengecualian selesai.');
    }
}