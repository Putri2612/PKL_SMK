<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelompokPkl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelompok_pkl', function (Blueprint $table) {
            $table->integer('id_kelompok')->primary();
            $table->unsignedBigInteger('dudi_id'); // Kolom untuk foreign key dudi_id
            $table->BigInteger('guru_nip'); // Kolom untuk foreign key dudi_id
            $table->timestamps();

            // Menambahkan foreign key constraint untuk dudi_id
            $table->foreign('dudi_id')
                ->references('id')
                ->on('dudi');
            
            // Menambahkan foreign key constraint untuk dudi_id
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
        Schema::dropIfExists('kelompok_pkl');
    }
}
