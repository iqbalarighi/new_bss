<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'jabatan',
        'kantor',
        'satker',
        'perusahaan',
        'dept',
        'role',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


     public function perusa()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan');
    }

public function kant()
    {
        return $this->belongsTo(KantorModel::class, 'kantor');
    }

public function jabat()
    {
        return $this->belongsTo(JabatanModel::class, 'jabatan');
    }

public function sat()
    {
        return $this->belongsTo(SatkerModel::class, 'satker');
    }
}
