<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatatanDudi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catatan_dudi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('siswa_nisn');
            $table->unsignedBigInteger('dudi_id');
            $table->text('catatan');
            $table->timestamps();
  

            // Menambahkan foreign key constraint untuk siswa_nisn
            $table->foreign('siswa_nisn')
            ->references('nisn')
            ->on('siswa');

            // Menambahkan foreign key constraint untuk dudi_id
            $table->foreign('dudi_id')
            ->references('id')
            ->on('dudi');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catatan_dudi');
    }
}
