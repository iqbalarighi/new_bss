<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatkerModel extends Model
{
    protected $table = 'satker';
    
    protected $fillable = [
        'perusahaan',
        'satuan_kerja',
    ];

    public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }  
}
