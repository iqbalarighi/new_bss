<?php

namespace App\Models;

use App\Models\DeptModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class PegawaiModel extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    // use HasProfilePhoto;
    use Notifiable;

protected $table = 'karyawan';
protected $guard = 'karyawan';

    protected $fillable = [
        'perusahaan',
        'nama_kantor',
        'dept',
        'jabatan',
        'satker',
        'nip',
        'password',
        'nama_lengkap',
        'tgl_lahir',
        'alamat',
        'domisili',
        'no_hp',
        'ko_drat',
        'bpjs_tk',
        'bpjs_sehat',
        'status',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // protected $appends = [
    //     'profile_photo_url',
    // ];
 public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }

public function kantor()
    {
        return $this->belongsTo(KantorModel::class, 'nama_kantor');
    }

public function jabat()
    {
        return $this->belongsTo(JabatanModel::class, 'jabatan');
    }

public function sat()
    {
        return $this->belongsTo(SatkerModel::class, 'satker');
    }

public function deptmn()
    {
        return $this->belongsTo(DeptModel::class, 'dept');
    }


}