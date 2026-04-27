<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengecualianAbsen extends Model
{
    protected $table = 'pengecualian_absen';
    
    protected $casts = [
        'tanggal' => 'array',
    ];
    
    protected $fillable = [
        'karyawan_id',
        'perusahaan',
        'nama_kantor',
        'keterangan',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal'
    ];

    public function karyawan()
    {
        return $this->belongsTo(PegawaiModel::class, 'karyawan_id', 'id');
    }
    
        public function kant()
    {
        return $this->belongsTo(KantorModel::class, 'nama_kantor');
    }

    public function getTanggalListAttribute()
    {
        $tanggalList = [];
    
        if (!empty($this->tanggal)) {
    
            if (is_array($this->tanggal)) {
                foreach ($this->tanggal as $item) {
                    $tanggalList = array_merge($tanggalList, explode(',', $item));
                }
            } else {
                $tanggalList = explode(',', $this->tanggal);
            }
    
            // bersihkan data
            $tanggalList = array_map('trim', $tanggalList);
            $tanggalList = array_filter($tanggalList);
        }
    
        return $tanggalList;
    }
    
    public function isTodayIncluded($today)
    {
        $list = $this->tanggal ?? [];
    
        $tanggalList = [];
    
        foreach ((array)$list as $tgl) {
            $tanggalList = array_merge($tanggalList, explode(',', $tgl));
        }
    
        $tanggalList = array_map('trim', $tanggalList);
        $tanggalList = array_filter($tanggalList);
    
        // permanent
        if (empty($tanggalList)) {
            return true;
        }
    
        return in_array($today, $tanggalList);
    }
}