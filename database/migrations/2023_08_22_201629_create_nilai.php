<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_nisn');
            $table->unsignedBigInteger('dudi_id');
            $table->unsignedBigInteger('kategori_id');
            $table->float('nilai_angka');
            $table->char('nilai_huruf');
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
        Schema::dropIfExists('nilai');
    }
}
