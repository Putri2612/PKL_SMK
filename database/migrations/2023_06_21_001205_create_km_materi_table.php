<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKmMateriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('km_materi', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->integer('guru_id');
            $table->integer('sesi_id');
            $table->integer('kelas_id');
            $table->string('nama_materi');
            $table->longText('teks');
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
        Schema::dropIfExists('km_materi');
    }
}
