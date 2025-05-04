<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LemburModel extends Model
{
    protected $table = 'lembur';
    
     protected $fillable = [
        'nip',
        'perusahaan',
        'tgl_absen',
        'jam_in',
        'foto_in',
        'lokasi_in',
        'jam_out',
        'foto_out',
        'lokasi_out',
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
