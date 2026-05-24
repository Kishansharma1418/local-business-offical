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
        Schema::create('invoice_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name')->nullable(); 
            $table->unsignedBigInteger('batch_id')->nullable(); 
            $table->decimal('quantity_ordered', 18, 2)->default(0);
            $table->decimal('quantity_delivered', 18, 2)->default(0);
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->decimal('discount_amount', 18, 2)->nullable();
            $table->decimal('gst_percent', 5, 2)->default(0);
            $table->decimal('gst_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->date('expiry_date')->nullable();
            $table->date('manufacturing_date')->nullable();
            $table->enum('status', [
                'Pending',
                'PartiallyDispatched',
                'Completed',
                'Cancelled'
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
        Schema::dropIfExists('invoice_order_details');
    }
};
