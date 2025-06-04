<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckModel extends Model
{
    protected $table = 'checkpoints';
    protected $fillable = ['nama', 'lokasi', 'deskripsi', 'kode_unik'];

    public function patrolLogs()
    {
        return $this->hasMany(PatrolLogModel::class);
    }
}
