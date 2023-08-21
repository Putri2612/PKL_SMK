<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogbook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logbook', function (Blueprint $table) {
            $table->integer('id_logbook')->primary();
            $table->date('tanggal');
            $table->string('siswa_nisn');
            $table->unsignedBigInteger('id_kelompok');
            $table->text('jenis_pekerjaan');
            $table->text('spesifikasi');
            $table->text('masalah');
            $table->text('penanganan');
            $table->text('alat_bahan');
            $table->integer('status');
            $table->timestamps();


            // Menambahkan foreign key constraint untuk siswa_nisn
            $table->foreign('siswa_nisn')
                ->references('nisn')
                ->on('siswa');

           // Menambahkan foreign key constraint untuk id_kelompok
           $table->foreign('id_kelompok')
           ->references('id_kelompok')
           ->on('kelompok_pkl');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logbook');
    }
}
