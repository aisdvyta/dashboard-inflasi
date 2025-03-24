<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('master_inflasis', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_pengguna');
            $table->string('nama', 64);
            $table->date('periode');
            $table->string('jenis_data_inflasi', 6);
            $table->date('upload_at');
            $table->foreign('id_pengguna')->references('id')->on('penggunas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_inflasis');
    }
};
