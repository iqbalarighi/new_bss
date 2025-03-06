<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatkerModel extends Model
{
    protected $table = 'satker';
    
    protected $fillable = [
        'satuan_kerja',
    ];
}
