<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name');
            $table->string('mobile')->nullable();
            $table->integer('age')->nullable();
            $table->integer('rating')->default(5);
            $table->text('review_text')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_reviews');
    }
};
