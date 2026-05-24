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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable(); 
            $table->date('date')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedInteger('currency_id')->nullable(); 
            $table->unsignedBigInteger('sales_person_id')->nullable();
            $table->unsignedInteger('payment_terms_id')->nullable(); 
            $table->enum('credit_limit', [
                'WithinLimit', 'OverLimit', 'Blocked']);
            $table->enum('approval_status', [
                'Pending', 'Approved', 'Rejected', 'AutoApproved'
            ])->default('Pending');
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('net_amount', 18, 2)->default(0);
            $table->decimal('outstanding_amount', 18, 2)->default(0);
            $table->enum('type',['draft', 'final'])->default('draft');
            $table->enum('payment_status', [
                'Pending', 'Partial', 'Paid'
            ])->default('Pending');
            $table->enum('status', [
                'Pending', 'PartiallyFulfilled', 'Completed', 'Cancelled'
            ])->default('Pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
