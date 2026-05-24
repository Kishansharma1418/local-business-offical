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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->unsignedBigInteger('invoice_order_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('invoice_order_id')->references('id')->on('invoice_orders')->onDelete('cascade');
            $table->decimal('amount_paid', 15, 2)->nullable();
            $table->decimal('amount_withheld', 15, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->date('payment_received_on')->nullable();
            $table->string('bank_charges')->nullable();
            $table->string('upload_receipt')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('notes')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('tax_deduction')->default('no');
             $table->enum('payment_status', [
                'Pending', 'Partial', 'Paid'
            ])->default('Pending');
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
        Schema::dropIfExists('payments');
    }
};
