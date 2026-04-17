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
        // Drop and recreate to change primary key from UUID to BigInt
        Schema::dropIfExists('orders');
        
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique()->nullable();
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->string('status')->default('New Order');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
