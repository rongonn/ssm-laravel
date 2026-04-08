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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('role_id')->constrained('roles');
            $table->string('title', 191);
            $table->string('permission', 191);
            $table->string('namespace', 191);
            $table->string('controller', 191);
            $table->string('method', 32);
            $table->string('action', 191);
            $table->string('uri', 191);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
