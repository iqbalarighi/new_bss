<?php

namespace App\Models;

use App\Models\DeptModel;
use App\Models\KantorModel;
use App\Models\PerusahaanModel;
use Illuminate\Database\Eloquent\Model;

class SatkerModel extends Model
{
    protected $table = 'satker';
    
    protected $fillable = [
        'perusahaan',
        'kantor',
        'dept_id',
        'satuan_kerja',
    ];

    public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }  

    public function kant()
    {
        return $this->belongsTo(KantorModel::class, 'kantor');
    }

    public function deptmn()
    {
        return $this->belongsTo(DeptModel::class, 'dept_id');
    }
}
