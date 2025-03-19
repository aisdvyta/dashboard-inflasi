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
        Schema::create('penggunas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedSmallInteger('id_satker');
            $table->unsignedTinyInteger('id_role');
            $table->string('nama', 64);
            $table->string('email', 64);
            $table->string('password', 64);
            $table->foreign('id_satker')->references('kode_satker')->on('master_satkers')->onDelete('cascade');
            $table->foreign('id_role')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunas');
    }
};
