<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PegawaiRollingModel;
use App\Models\PegawaiModel;
use Carbon\Carbon;

class RollingPegawai extends Command
{
    protected $signature = 'app:rolling-pegawai';

    public function handle()
    {
        $today = now()->toDateString();

        // ================= EKSEKUSI ROLLING =================
        $data = PegawaiRollingModel::where('tanggal_efektif', $today)
            ->where('is_executed', false)
            ->get();

        foreach ($data as $item) {

            PegawaiModel::where('id', $item->pegawai_id)->update([
                'perusahaan' => $item->perusahaan,
                'nama_kantor' => $item->kantor,
                'dept' => $item->dept,
                'satker' => $item->satker,
                'jabatan' => $item->jabatan,
            ]);

            $item->update(['is_executed' => true]);
        }

        // ================= CLEANUP DATA LAMA =================
        $expiredDate = Carbon::now()->subDays(3)->toDateString();

        $deleted = PegawaiRollingModel::where('is_executed', true)
            ->whereDate('tanggal_efektif', '<=', $expiredDate)
            ->delete();

        $this->info("Cleanup rolling: {$deleted} data dihapus");

        $this->info('Rolling pegawai selesai');
    }
}
