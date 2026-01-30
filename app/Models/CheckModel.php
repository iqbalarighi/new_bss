<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckModel extends Model
{
    protected $table = 'checkpoints';
    protected $fillable = ['nama', 'lokasi', 'perusahaan', 'kantor', 'deskripsi', 'kode_unik'];

    public function patrolLogs()
    {
        return $this->hasMany(PatrolLogModel::class);
    }

    public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }  

    public function kant()
    {
        return $this->belongsTo(KantorModel::class, 'kantor');
    }

}
