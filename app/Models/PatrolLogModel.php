<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolLogModel extends Model
{
    protected $table = 'patrol_logs';
    protected $fillable = ['user_id', 'checkpoint_id', 'perusahaan', 'kantor', 'keterangan', 'shift', 'foto', 'waktu_scan'];

    public function checkpoint()
    {
        return $this->belongsTo(CheckModel::class);
    }

    public function user()
    {
        return $this->belongsTo(PegawaiModel::class);
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
