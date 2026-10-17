<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete(); // coupon belongs to a store
            $table->string('title');                      // "Get 30% off your first order"
            $table->text('description')->nullable();
            $table->string('type', 10)->default('code');  // 'code' = Show Code, 'deal' = Get Deal
            $table->string('code')->nullable();           // SAVE30
            $table->string('discount')->nullable();       // "30% OFF"
            $table->string('url')->nullable();            // empty = use the store URL
            $table->boolean('verified')->default(false);
            $table->date('expires_at')->nullable();       // hidden after this day
            $table->timestamps();

            $table->index(['store_id', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
