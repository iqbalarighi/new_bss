<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShiftModel extends Model
{
    use HasFactory;

    protected $table = 'shift';

    protected $fillable = [
        'perusahaan',
        'shift',
        'jam_masuk',
        'jam_keluar',
        'kantor_id',
        'dept_id',
        'satker_id',
    ];

     public function sat()
    {
        return $this->belongsTo(SatkerModel::class, 'satker_id');
    }

    public function kant()
    {
        return $this->belongsTo(KantorModel::class, 'kantor_id');
    }

    public function deptmn()
    {
        return $this->belongsTo(DeptModel::class, 'dept_id');
    }
}
