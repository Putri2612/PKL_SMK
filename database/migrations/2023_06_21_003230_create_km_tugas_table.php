<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKmTugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('km_tugas', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->integer('guru_id');
            $table->integer('kelas_id');
            $table->integer('sesi_id');
            $table->string('nama_tugas');
            $table->longText('teks');
            $table->string('due_date');
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
        Schema::dropIfExists('km_tugas');
    }
}
