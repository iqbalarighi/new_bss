<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanModel extends Model
{
    protected $table = 'jabatan';
    
    protected $fillable = [
        'perusahaan',
        'kantor_id',
        'dept_id',
        'satker_id',
        'jabatan',
    ];

public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }  

    public function kant()
    {
        return $this->belongsTo(KantorModel::class, 'kantor_id');
    }

    public function deptmn()
    {
        return $this->belongsTo(DeptModel::class, 'dept_id');
    }

    public function sat()
    {
        return $this->belongsTo(SatkerModel::class, 'satker_id');
    }
}
