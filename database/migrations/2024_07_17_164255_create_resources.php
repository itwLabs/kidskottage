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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string("url");
            $table->string("size");
            $table->string("type");
            $table->string("name");
            $table->string("file_name");
            $table->string("alt");
            $table->string('reso_type', 50)->nullable();
            $table->string('resoable_type', 50)->nullable();
            $table->unsignedInteger('resoable_id')->nullable();
            $table->unsignedInteger('moved')->default(0);
            $table->unsignedInteger('order')->default(1);
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
