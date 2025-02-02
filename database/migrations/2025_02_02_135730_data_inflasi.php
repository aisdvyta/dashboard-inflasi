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
            $table->id();
            $table->string('username');
            $table->string('data_name');
            $table->string('period'); // format MM/YYYY
            $table->string('category');
            $table->string('file_path'); // link to stored CSV file
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
};
