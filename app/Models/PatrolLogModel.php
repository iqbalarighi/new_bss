<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolLogModel extends Model
{
    protected $table = 'patrol_logs';
    protected $fillable = ['user_id', 'checkpoint_id', 'keterangan', 'foto', 'waktu_scan'];

    public function checkpoint()
    {
        return $this->belongsTo(Checkpoint::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
