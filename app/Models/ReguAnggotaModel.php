<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReguAnggotaModel extends Model
{
    protected $table = 'regu_anggota';

    protected $fillable = ['regu_id', 'pegawai_id'];

    public $timestamps = true;

    public function pegawai()
    {
        return $this->belongsTo(PegawaiModel::class, 'pegawai_id');
    }

    public function regu()
{
    return $this->belongsTo(ReguModel::class, 'regu_id');
}
}