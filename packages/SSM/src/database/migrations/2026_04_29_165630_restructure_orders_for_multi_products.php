<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing orders table if it exists
        Schema::dropIfExists('orders');

        // Recreate orders table without product_id
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('delivery_charge', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('status')->default('New Order');
            $table->string('source')->default('Website');
            $table->timestamps();
        });

        // Create order_items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->uuid('product_id'); // We are using UUIDs for products
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2); // Price per unit at purchase time
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
