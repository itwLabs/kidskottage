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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->double("sub_amount");
            $table->double("discount_amount")->default(0);
            $table->double("total_amount");
            $table->double("paid_amount")->default(0);
            $table->string("discount_code", 20)->nullable();
            $table->string("payment_method", 20)->default("Cash on delivery");
            $table->enum("state", ["pending", "processing", "ready", "delivered"])->default("pending");
            $table->enum("status", ["active", "cancelled"])->default("active");
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
