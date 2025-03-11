<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenModel extends Model
{
    protected $table = 'absensi';
    
     protected $fillable = [
        'nip',
        'tgl_absen',
        'jam_in',
        'foto_in',
        'lokasi_in',
        'jam_out',
        'foto_out',
        'lokasi_out',
    ];
}
