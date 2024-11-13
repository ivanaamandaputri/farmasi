<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturTable extends Migration
{
    public function up()
    {
        Schema::create('retur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('obat_id');
            $table->unsignedBigInteger('jenis_obat_id');
            $table->unsignedBigInteger('user_id');
            $table->string('password');
            $table->integer('jumlah');
            $table->text('alasan_retur')->nullable();
            $table->string('status')->default('Diretur');
            $table->timestamps();

            // Menambahkan foreign key constraints
            $table->foreign('transaksi_id')->references('id')->on('transaksi');
            $table->foreign('obat_id')->references('id')->on('obat');
            $table->foreign('jenis_obat_id')->references('id')->on('jenis_obat');
            $table->foreign('user_id')->references('id')->on('user');
        });
    }

    public function down()
    {
        Schema::dropIfExists('retur');
    }
}
