<?php

namespace App\Models;

use App\Models\KantorModel;
use App\Models\PerusahaanModel;
use Illuminate\Database\Eloquent\Model;

class DeptModel extends Model
{
    protected $table = 'departemen';
    
    protected $fillable = [
        'perusahaan',
        'nama_kantor',
        'nama_dept',
    ];

    public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }  

    public function kantor()
    {
        return $this->belongsTo(KantorModel::class, 'nama_kantor');
    }
}