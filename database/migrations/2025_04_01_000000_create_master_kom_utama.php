<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('master_kom_utama', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kom')->unique();
            $table->string('nama_kom');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_kom_utama');
    }
};
