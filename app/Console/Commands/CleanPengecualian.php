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

        PengecualianAbsen::whereNotNull('tanggal')
            ->select('id', 'tanggal')
            ->chunkById(200, function ($rows) use ($today) {

                foreach ($rows as $item) {

                    $tanggalList = is_array($item->tanggal)
                        ? $item->tanggal
                        : explode(',', $item->tanggal);

                    $tanggalList = array_filter(array_map('trim', $tanggalList));

                    $tanggalBaru = [];

                    foreach ($tanggalList as $tgl) {
                        if ($tgl >= $today) {
                            $tanggalBaru[] = $tgl;
                        }
                    }

                    // 🔥 kalau kosong → delete
                    if (empty($tanggalBaru)) {
                        DB::table('pengecualian_absen')
                            ->where('id', $item->id)
                            ->delete();
                        continue;
                    }

                    // 🔥 kalau tidak berubah → skip
                    if ($tanggalBaru === array_values($tanggalList)) {
                        continue;
                    }

                    // 🔥 update
                    $table = (new PengecualianAbsen)->getTable();
                    
                    DB::table($table)
                        ->where('id', $item->id)
                        ->update([
                            'tanggal' => json_encode(array_values($tanggalBaru))
                        ]);
                }
            });

        $this->info('Pembersihan pengecualian selesai.');
    }
}