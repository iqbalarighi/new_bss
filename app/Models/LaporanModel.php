<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanModel extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'perusahaan',
        'kantor',
        'dept',
        'satker',
        'jabatan',
        'user_id',
        'no_lap',
        'personil',
        'kegiatan',
        'keterangan',
        'foto',
    ];

    // Fungsi untuk generate no_lap
    public static function generateNoLap()
    {
        // Ambil bulan (2 digit) dan 2 digit terakhir tahun
        $bulanTahun = date('m') . substr(date('Y'), -2);

        // Ambil nomor urut terakhir
        $lastRecord = self::where('no_lap', 'like', 'LAP-' . $bulanTahun . '-%')->orderBy('no_lap', 'desc')->first();

        // Jika ada record sebelumnya, increment nomor urut
        if ($lastRecord) {
            $lastNumber = intval(substr($lastRecord->no_lap, -4));
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0000';
        }

        // Format no_lap
        return 'LAP-' . $bulanTahun . '-' . $nextNumber;
    }

public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }

public function kant()
    {
        return $this->belongsTo(KantorModel::class, 'kantor');
    }

public function jabat()
    {
        return $this->belongsTo(JabatanModel::class, 'jabatan');
    }

public function sat()
    {
        return $this->belongsTo(SatkerModel::class, 'satker');
    }

public function deptmn() {
    return $this->belongsTo(DeptModel::class, 'dept');
    }

public function usr() {
    return $this->belongsTo(PegawaiModel::class, 'user_id');
    }
}