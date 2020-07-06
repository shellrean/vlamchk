<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentifyServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identify_server', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_server');
            $table->char('isregister');
            $table->string('serial_number');
            $table->integer('peserta')->nullable();
            $table->integer('matpel')->nullable();
            $table->integer('banksoal')->nullable();
            $table->integer('soal')->nullable();
            $table->integer('pilihan_soal')->nullable();
            $table->integer('gambar')->nullable();
            $table->integer('jadwal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('identify_server');
    }
}
