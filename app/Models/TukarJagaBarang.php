<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TukarJagaBarang extends Model
{
    protected $table = 'tukar_jaga_barang';

    protected $fillable = [
        'tukar_jaga_id',
        'nama_barang',
        'jumlah',
        'kondisi'
    ];

    public function tukarJaga()
    {
        return $this->belongsTo(TukarJaga::class);
    }
}
