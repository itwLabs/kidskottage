<?php

use App\Enums\Status;
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
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('feature_no')->default(0);
            $table->integer('on_trending')->default(0);
            $table->boolean('on_footer')->default(false);
            $table->boolean('isActive')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->default(null)->constrained('category')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
