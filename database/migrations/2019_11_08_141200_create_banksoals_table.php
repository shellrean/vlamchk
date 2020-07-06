<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banksoals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_banksoal',50);
            $table->integer('jumlah_soal');
            $table->integer('jumlah_pilihan');
            $table->bigInteger('matpel_id');
            $table->bigInteger('jumlah_soal_esay');
            $table->bigInteger('directory_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banksoals');
    }
}
