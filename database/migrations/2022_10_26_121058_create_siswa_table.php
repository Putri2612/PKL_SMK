<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->integer('nisn')->primary();
            $table->string('nama_siswa');
            $table->string('jenis_kelamin');
            $table->string('golongan_darah');
            $table->integer('kelas_id');
            $table->integer('jurusan_id');
            $table->integer('tahun_ajar_id');
            $table->integer('no_telp');
            $table->string('alamat');
            $table->string('password');
            $table->string('avatar');
            $table->integer('role');
            $table->boolean('is_active');
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
        Schema::dropIfExists('siswas');
    }
}
