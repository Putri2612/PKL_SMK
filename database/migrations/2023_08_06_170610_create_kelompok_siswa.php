<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelompokSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelompok_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelompok_id'); 
            $table->string('siswa_nisn');
            $table->timestamps();

            // Menambahkan foreign key constraint untuk dudi_id
            $table->foreign('kelompok_id')
                ->references('id_kelompok')
                ->on('kelompok_pkl');
            
            // Menambahkan foreign key constraint untuk siswa_nisn
            $table->foreign('siswa_nisn')
                ->references('nisn')
                ->on('siswa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelompok_siswa');
    }
}
