<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKunjungan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->BigInteger('guru_nip');
            $table->unsignedBigInteger('dudi_id');
            $table->text('catatan');
            $table->text('foto');
            $table->timestamps();

             // Menambahkan foreign key constraint untuk siswa_nisn
            $table->foreign('guru_nip')
            ->references('nip')
            ->on('guru');
            
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
        Schema::dropIfExists('kunjungan');
    }
}
