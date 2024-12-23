<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    // Pastikan kolom 'tanggal' diperlakukan sebagai objek Carbon
    protected $dates = ['tanggal'];

    protected $fillable = [
        'obat_id',
        'user_id',
        'jumlah',
        'total',
        'status',
        'alasan_penolakan',
        'tanggal',
        'acc'
    ];
    protected $casts = [
        'tanggal' => 'datetime', // Mengubah 'tanggal' menjadi objek Carbon
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function jenisobat()
    {
        return $this->belongsTo(JenisObat::class, 'jenis_obat_id');
    }

    // Di dalam model Transaksi
    public function user()
    {
        return $this->belongsTo(User::class);  // Transaksi berhubungan dengan satu User
    }

    public function retur()
    {
        return $this->hasOne(Retur::class);
    }
}
