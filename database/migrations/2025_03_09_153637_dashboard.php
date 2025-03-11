<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('id_inflasi');
            $table->unsignedSmallInteger('id_satker');
            $table->unsignedInteger('id_kom')->nullable();
            $table->unsignedTinyInteger('id_flag')->nullable();
            $table->decimal('inflasi_MtM', 5, 2);
            $table->decimal('inflasi_YtD', 5, 2);
            $table->decimal('inflasi_YoY', 5, 2);
            $table->decimal('andil_MtM', 5, 2);
            $table->decimal('andil_YtD', 5, 2);
            $table->decimal('andil_YoY', 5, 2);
            $table->date('created_at');
            $table->foreign('id_inflasi')->references('id')->on('data_inflasis')->onDelete('cascade');
            $table->foreign('id_satker')->references('id')->on('master_satkers')->onDelete('cascade');
            $table->foreign('id_kom')->references('id')->on('master_komoditas')->onDelete('cascade');
            $table->foreign('id_flag')->references('id')->on('master_flags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dashboards');
    }
};
