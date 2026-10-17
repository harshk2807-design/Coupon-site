<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Temu
            $table->string('slug')->unique();               // temu  -> temu.yourdomain.com
            $table->string('logo')->nullable();             // uploaded file path OR full image URL
            $table->string('url')->nullable();              // store / affiliate link
            $table->decimal('rating', 2, 1)->default(0);    // 0.0 - 5.0
            $table->string('button_text')->nullable();      // "Activate 10% Cash Back"
            $table->text('about')->nullable();              // About / FAQ text
            $table->boolean('is_featured')->default(false); // show on homepage first
            $table->timestamps();
        });

        // Many-to-many: a store can be in many categories, a category has many stores
        Schema::create('category_store', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'store_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_store');
        Schema::dropIfExists('stores');
    }
};
