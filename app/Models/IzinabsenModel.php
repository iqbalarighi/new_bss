<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzinabsenModel extends Model
{
     protected $table = 'req_izin';
    
     protected $fillable = [
        'perusahaan',
        'nama_kantor',
        'nip',
        'tanggal',
        'jenis_izin',
        'keterangan',
        'foto',
        'status_approve',
    ];

    public function pegawai()
    {
        return $this->belongsTo(PegawaiModel::class, 'nip');
    }  

    public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }

    public function kantor()
    {
        return $this->belongsTo(KantorModel::class, 'nama_kantor');
    }
}
