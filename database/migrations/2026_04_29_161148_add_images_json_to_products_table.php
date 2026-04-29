<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate existing single image_url values into a JSON array in the same column
        // First, add a temp column for the new JSON data
        Schema::table('products', function (Blueprint $table) {
            $table->json('images_json')->nullable()->after('image_url');
        });

        // Copy existing image_url into the JSON array
        DB::table('products')->get()->each(function ($product) {
            $images = [];
            if ($product->image_url) {
                $images[] = $product->image_url;
            }
            DB::table('products')->where('id', $product->id)->update([
                'images_json' => json_encode($images),
            ]);
        });

        // Rename columns: drop old, rename new
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('images_json', 'image_url');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_url_old')->nullable()->after('image_url');
        });

        DB::table('products')->get()->each(function ($product) {
            $images = json_decode($product->image_url, true);
            $first = is_array($images) ? ($images[0] ?? null) : null;
            DB::table('products')->where('id', $product->id)->update([
                'image_url_old' => $first,
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('image_url_old', 'image_url');
        });
    }
};
