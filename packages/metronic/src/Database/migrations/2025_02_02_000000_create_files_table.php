<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('file_title')->nullable();
            $table->string('type', 100)->nullable();
            $table->string('extension', 32)->nullable();
            $table->string('size', 32)->default(0);
            $table->integer('order')->default(0);
            $table->tinyInteger('default')->default(0);
            $table->morphs('fileable');
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
        Schema::dropIfExists('files');
    }
    
};
