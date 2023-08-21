<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDudi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dudi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dudi');
            $table->string('no_telp');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar');
            $table->integer('role');
            $table->boolean('is_active');
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
        Schema::dropIfExists('dudi');
    }
}
