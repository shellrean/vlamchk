<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabanPesertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jawaban_pesertas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('banksoal_id');
            $table->bigInteger('soal_id');
            $table->text('esay')->nullable();
            $table->bigInteger('peserta_id');
            $table->bigInteger('jadwal_id');
            $table->bigInteger('jawab');
            $table->char('ragu_ragu');
            $table->char('iscorrect');
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
        Schema::dropIfExists('jawaban_pesertas');
    }
}
