<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nama_role', 24);
        });

        Schema::create('master_satkers', function (Blueprint $table) {
            $table->unsignedSmallInteger('kode_satker')->primary();
            $table->string('nama_satker', 24);
        });

        Schema::create('master_wilayahs', function (Blueprint $table) {
            $table->unsignedSmallInteger('kode_wil')->primary();
            $table->string('nama_wil', 24);
        });

        Schema::create('master_komoditas', function (Blueprint $table) {
            $table->string('kode_kom',10)->primary();
            $table->unsignedTinyInteger('flag')->index();
            $table->text('nama_kom');
            $table->unsignedTinyInteger('flag_2')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('master_satkers');
        Schema::dropIfExists('master_wilayahs');
        Schema::dropIfExists('master_komoditas');
    }
};
