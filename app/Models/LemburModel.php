<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LemburModel extends Model
{
    protected $table = 'lembur';
    
     protected $fillable = [
        'nip',
        'perusahaan',
        'kantor',
        'dept',
        'satker',
        'area_kerja',
        'uraian',
        'tgl_absen',
        'jam_in',
        'foto_in',
        'lokasi_in',
        'jam_out',
        'foto_out',
        'lokasi_out',
        'aprv_by_spv',
        'aprv_by_adm',
    ];

    public function pegawai()
    {
        return $this->belongsTo(PegawaiModel::class, 'nip');
    }

    public function spv()
    {
        return $this->belongsTo(PegawaiModel::class, 'aprv_by_spv');
    }  

        public function shifts()
    {
        return $this->belongsTo(ShiftModel::class, 'shift');
    }

        public function kant()
    {
        return $this->belongsTo(KantorModel::class, 'kantor');
    }

        public function sat()
    {
        return $this->belongsTo(SatkerModel::class, 'satker');
    }

    public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }

    public function deptmn()
    {
        return $this->belongsTo(DeptModel::class, 'dept');
    }
}
