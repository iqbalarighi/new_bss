<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KantorModel extends Model
{
    protected $table = 'kantor';

    protected $fillable = [
        'perusahaan',
        'nama_kantor',
        'alamat',
        'lokasi',
    ];
}
