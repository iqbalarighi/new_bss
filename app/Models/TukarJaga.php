<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KantorModel;
use App\Models\PerusahaanModel;

class TukarJaga extends Model
{
    protected $table = 'tukar_jaga';

    protected $fillable = [
        'perusahaan_id',
        'kantor_id',
        'user_id',
        'tanggal',
        'shift',
        'lokasi_gedung',
        'no_lap',
        'petugas_lama',
        'petugas_baru',
        'kejadian',
    ];

    public static function generateNoLap()
    {
        // Ambil bulan & 2 digit tahun
        $bulanTahun = date('m') . substr(date('Y'), -2);

        // Ambil nomor terakhir
        $lastRecord = self::where('no_lap', 'like', 'STJ-' . $bulanTahun . '-%')
            ->orderBy('no_lap', 'desc')
            ->first();

        if ($lastRecord) {
            $lastNumber = intval(substr($lastRecord->no_lap, -4));
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return 'STJ-' . $bulanTahun . '-' . $nextNumber;
    }

    public function karyawan()
    {
        return $this->belongsTo(PegawaiModel::class, 'user_id');
    }

   public function barang()
	{
	    return $this->hasMany(TukarJagaBarang::class, 'tukar_jaga_id');
	}

    public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan_id');
    }  

    public function kant()
    {
        return $this->belongsTo(KantorModel::class, 'kantor_id');
    }

}
