<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat');
            $table->string('dosis');
            $table->enum('jenis', ['tablet', 'kapsul', 'botol', 'dus']);
            $table->integer('jumlah');
            $table->double('harga');
            $table->string('nama_pemesan');
            $table->enum('ruangan', [
                'Instalasi Farmasi',
                'puskesmas Kaligangsa',
                'puskesmas Margadana',
                'puskesmas Tegal Barat',
                'puskesmas Debong Lor',
                'puskesmas Tegal Timur',
                'puskesmas Slerok',
                'puskesmas Tegal Selatan',
                'puskesmas Bandung'
            ]);
            $table->unsignedBigInteger('obat_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('obat_id')->references('id')->on('obat');
            $table->foreign('user_id')->references('id')->on('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
