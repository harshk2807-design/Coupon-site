<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Categories in two levels:
 *   Games                     (main category  -> parent_id = NULL)
 *     └── Board & Card Games  (subcategory    -> parent_id = id of "Games")
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();              // used in the URL: /category/board-card-games
            $table->foreignId('parent_id')->nullable()     // NULL = main category
                ->constrained('categories')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0); // lower = shown first
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
