<?php

use App\Enums\SectionType;
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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum("type", array_column(SectionType::cases(), "value"))->default('service');
            $table->text('data1')->nullable();
            $table->text('data2')->nullable();
            $table->text('data3')->nullable();
            $table->text('data4')->nullable();
            $table->boolean('isActive')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
