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
        Schema::create('data_inflasis', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->uuid('id_pengguna');
            $table->string('nama', 64);
            $table->date('periode');
            $table->string('jenis_data_inflasi', 4);
            $table->date('upload_at');
            $table->foreign('id_pengguna')->references('id')->on('penggunas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_inflasis');
    }
};
