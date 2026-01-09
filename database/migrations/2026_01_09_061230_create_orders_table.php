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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("customer_id")->constrained("customers")->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp("order_date")->useCurrent();
            $table->decimal("total_amount", 15, 2);
            $table->string('payment_method', 50)->default('midtrans');
            $table->string('payment_type', 50)->nullable(); 
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('payment_response')->nullable();
            $table->enum("status", ["pending", "paid", "cancelled"])->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
