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
        Schema::create('product_age_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained("products")->cascadeOnDelete();
            $table->foreignId('age_group_id')->constrained("age_groups")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_age_group');
    }
};
