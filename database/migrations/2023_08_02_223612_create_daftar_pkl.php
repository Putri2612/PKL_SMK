<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaftarPkl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_pkl', function (Blueprint $table) {
            $table->id('id_daftar'); // Mengganti primary key menjadi id_daftar
            $table->text('surat_balasan')->nullable(); // Menambah kolom surat_balasan
            $table->string('siswa_nisn'); // Kolom untuk foreign key siswa_nisn
            $table->unsignedBigInteger('dudi_id'); // Kolom untuk foreign key dudi_id
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
        Schema::dropIfExists('daftar_pkl');
    }
}
