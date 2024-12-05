<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;  // Add this line
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to prevent duplication
        DB::table('users')->truncate();

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
