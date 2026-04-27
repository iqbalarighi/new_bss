<?php

namespace App\Models;

use App\Models\PegawaiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenModel extends Model
{
    protected $table = 'absensi';
    
     protected $fillable = [
        'nip',
        'shift',
        'perusahaan',
        'kantor',
        'tgl_absen',
        'jam_in',
        'foto_in',
        'lokasi_in',
        'jam_out',
        'foto_out',
        'lokasi_out',
        'face_verified',
        'face_score',
        'is_fallback',
        'verified_at',
        'face_status',
    ];

    public function pegawai()
    {
        return $this->belongsTo(PegawaiModel::class, 'nip');
    }  

        public function shifts()
    {
        return $this->belongsTo(ShiftModel::class, 'shift');
    }
}
