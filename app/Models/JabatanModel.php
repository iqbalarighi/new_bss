<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanModel extends Model
{
    protected $table = 'jabatan';
    
    protected $fillable = [
        'perusahaan',
        'jabatan',
    ];

public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }  
}
