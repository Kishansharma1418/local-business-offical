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
        Schema::create('refund_orders', function (Blueprint $table) {
            $table->id();
            $table->string('refund_order_number')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('credit_note_id')->nullable();
            $table->unsignedBigInteger('sales_order_id')->nullable();
            $table->date('refund_order_date')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('reference_number')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_orders');
    }
};
