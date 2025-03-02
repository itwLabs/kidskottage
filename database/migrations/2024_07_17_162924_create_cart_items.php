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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->integer('cart_id');
            $table->unsignedInteger('qty')->default(1);
            $table->unsignedInteger('price');
            $table->unsignedInteger('discount')->default(0);
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('attr_id')->nullable();
            $table->json('attr')->nullable();
            // $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
