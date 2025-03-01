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
        'jam_out',
        'foto_in',
        'foto_out',
        'lokasi',
    ];
}
