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

        Schema::create('master_flags', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->string('desk_flag',24);
        });

        Schema::create('master_komoditas', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('nama_kom',24);
        });

        Schema::create('master_satkers', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary();
            $table->string('nama_satker', 24);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('master_flags');
        Schema::dropIfExists('master_komoditas');
        Schema::dropIfExists('master_satkers');
    }
};
