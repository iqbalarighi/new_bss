<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiRollingModel extends Model
{
    protected $table = 'pegawai_rolling';

    protected $fillable = [
        'pegawai_id',
        'perusahaan',
        'kantor',
        'dept',
        'satker',
        'jabatan',
        'tanggal_efektif',
        'is_executed'
    ];

    public function pegawai()
    {
        return $this->belongsTo(PegawaiModel::class, 'pegawai_id');
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

public function deptmn()
    {
        return $this->belongsTo(DeptModel::class, 'dept');
    }
}