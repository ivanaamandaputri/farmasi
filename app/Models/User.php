<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';

    protected $fillable = [
        'nip',
        'password',
        'level',
        'nama_pegawai',
        'jabatan',
        'ruangan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setNamaPegawaiAttribute($value)
    {
        $this->attributes['nama_pegawai'] = ucwords(strtolower($value)); // Ubah huruf pertama menjadi kapital
    }

    public function setNipAttribute($value)
    {
        $this->attributes['nip'] = strtoupper($value); // Ubah menjadi huruf kapital
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value); // Enkripsi password
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
