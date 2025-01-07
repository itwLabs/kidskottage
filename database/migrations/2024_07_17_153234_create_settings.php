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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('opening_hours')->nullable();
            $table->string('email');
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->string('x_link')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('insta_link')->nullable();
            $table->string('tiktok_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('address1', 500)->nullable();
            $table->string('address2', 500)->nullable();
            $table->string('map_link', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
