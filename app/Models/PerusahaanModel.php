<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerusahaanModel extends Model
{
    protected $table = 'perusahaan';
    
    protected $fillable = [
        'perusahaan',
        'alamat',
        'no_tlp',
        'logo',
        'status',
    ];
}
