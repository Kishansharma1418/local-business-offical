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
        Schema::create('purchase_orders', function (Blueprint $table) {  
            $table->id();
            $table->string('po_number')->unique()->nullable();
            $table->date('po_date')->nullable();
            $table->unsignedBigInteger('vendor_id')->index()->nullable();
            $table->unsignedBigInteger('broker_id')->index()->nullable();
            $table->unsignedBigInteger('branch_id')->index()->nullable();
            $table->string('currency_id')->index()->nullable();
            $table->string('delivery_term')->nullable();
            $table->date('delivery_date')->nullable(); 
            $table->unsignedBigInteger('payment_term_id')->index()->nullable();
             $table->enum('status', [
                'draft',
                'approved',
                'sent',
                'accepted',
                'partialreceived',
                'completed',
                'rejected'
            ])->default('draft');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->string('invoice_file')->nullable();
            $table->decimal('total_amount', 18, 2)->nullable();
            $table->decimal('tax_amount', 18, 2)->default(0)->nullable();
            $table->decimal('discount_percent', 18, 2)->default(0)->nullable();
            $table->decimal('discount_amount', 18, 2)->default(0)->nullable();
            $table->decimal('net_amount', 18, 2)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
