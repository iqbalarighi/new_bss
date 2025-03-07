<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class KaryawanModel extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    // use HasProfilePhoto;
    use Notifiable;

protected $table = 'karyawan';

    protected $fillable = [
        'perusahaan',
        'nama_kantor',
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
}