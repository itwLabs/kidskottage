<?php

use App\Enums\PaymentMethod;
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
        Schema::create('transaction_history', function (Blueprint $table) {
            $table->id();
            $table->string("transaction_id", 20)->nullable();
            $table->integer("order_id")->nullable();
            $table->float("amount")->nullable();
            $table->enum("payment_method", array_column(PaymentMethod::cases(), "value"));
            $table->text("request")->nullable();
            $table->text("response")->nullable();
            $table->text("message")->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_history');
    }
};
