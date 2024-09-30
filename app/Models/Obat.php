<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'dosis',
        'jenis',
        'jumlah',
        'harga',
    ];

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
}
