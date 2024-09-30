<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'nip' => '123',
                'password' => bcrypt('pwd123'),
                'level' => 'admin',
                'nama_pegawai' => 'Budi Santoso',
                'jabatan' => 'Kepala Apotik',
                'ruangan' => 'Instalasi Farmasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '321',
                'password' => bcrypt('pwd123'),
                'level' => 'operator',
                'nama_pegawai' => 'Myra Dwi',
                'jabatan' => 'Apoteker',
                'ruangan' => 'puskesmas Kaligangsa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
