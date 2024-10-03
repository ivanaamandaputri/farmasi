<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';

    protected $fillable = [
        'nama_obat',
        'dosis',
        'jenis',
        'jumlah',
        'harga',
        'nama_pemesan',
        'ruangan',
        'obat_id',
        'user_id',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setNamaObatAttribute($value)
    {
        $this->attributes['nama_obat'] = ucwords(strtolower($value)); // Ubah huruf pertama menjadi kapital
    }

    public function setDosisAttribute($value)
    {
        $this->attributes['dosis'] = ucwords(strtolower($value)); // Ubah huruf pertama menjadi kapital
    }

    public function setJenisAttribute($value)
    {
        $this->attributes['jenis'] = ucwords(strtolower($value)); // Ubah huruf pertama menjadi kapital
    }

    public function setNamaPemesanAttribute($value)
    {
        $this->attributes['nama_pemesan'] = ucwords(strtolower($value)); // Ubah huruf pertama menjadi kapital
    }
}
