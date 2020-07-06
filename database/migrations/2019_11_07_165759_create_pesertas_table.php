<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesertas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('jurusan_id')->nullable();
            $table->integer('agama_id')->nullable();
            $table->string('name_server');
            $table->string('no_ujian');
            $table->string('nama');
            $table->string('password');
            $table->string('api_token')->nullable();
            $table->integer('sesi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesertas');
    }
}
