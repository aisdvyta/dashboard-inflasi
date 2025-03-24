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
        Schema::create('detail_inflasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inflasi');
            $table->unsignedSmallInteger('id_wil');
            $table->string('id_kom',10)->nullable();
            $table->unsignedTinyInteger('id_flag')->nullable();
            $table->decimal('inflasi_MtM', 5, 2);
            $table->decimal('inflasi_YtD', 5, 2);
            $table->decimal('inflasi_YoY', 5, 2);
            $table->decimal('andil_MtM', 5, 2);
            $table->decimal('andil_YtD', 5, 2);
            $table->decimal('andil_YoY', 5, 2);
            $table->date('created_at');
            $table->foreign('id_inflasi')->references('id')->on('master_inflasis')->onDelete('cascade');
            $table->foreign('id_wil')->references('kode_wil')->on('master_wilayahs')->onDelete('cascade');
            $table->foreign('id_kom')->references('kode_kom')->on('master_komoditas')->onDelete('cascade');
            $table->foreign('id_flag')->references('flag')->on('master_komoditas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_inflasis');
    }
};
