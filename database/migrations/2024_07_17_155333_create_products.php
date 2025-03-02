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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_name')->nullable();
            $table->integer('rating')->default(0);
            $table->decimal('price');
            $table->decimal('discount')->default(0);
            $table->integer('stock')->default(0);
            $table->string('attr')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->mediumText('description');
            $table->text('keywords')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('isActive')->default(true);
            $table->foreignId('brand_id')->nullable()->constrained('brands')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
