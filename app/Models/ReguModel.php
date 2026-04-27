<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReguModel extends Model
{
    protected $table = 'regu';

    protected $fillable = ['nama_regu', 'perusahaan', 'supervisor_id', 'danru_id'];

    public function anggota()
    {
        return $this->hasMany(ReguAnggotaModel::class, 'regu_id');
    }

    public function danru()
{
    return $this->belongsTo(PegawaiModel::class, 'danru_id');
}

public function supervisor()
{
    return $this->belongsTo(PegawaiModel::class, 'supervisor_id');
}

}