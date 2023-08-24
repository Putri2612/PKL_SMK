<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('siswa_nisn');
            $table->BigInteger('guru_nip');
            $table->text('ke');
            $table->text('catatan');
            $table->timestamps();
  

        // Menambahkan foreign key constraint untuk siswa_nisn
        $table->foreign('siswa_nisn')
        ->references('nisn')
        ->on('siswa');

        // Menambahkan foreign key constraint untuk siswa_nisn
        $table->foreign('guru_nip')
        ->references('nip')
        ->on('guru');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoring');
    }
}
