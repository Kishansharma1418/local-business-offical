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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id')->index()->nullable();
            $table->unsignedBigInteger('raw_material_id')->index()->nullable();
            $table->decimal('quantity_ordered', 18, 2)->nullable();
            $table->unsignedBigInteger('uom_id')->index()->nullable();
            $table->decimal('unit_price', 18, 2)->nullable();
            $table->decimal('discount_percent', 18, 2)->default(0)->nullable();
            $table->decimal('discount_amount', 18, 2)->default(0)->nullable();
            $table->string('gst_percent')->nullable();
            $table->string('gst_amount')->nullable();
            $table->decimal('total_price', 18, 2)->nullable();
            $table->decimal('received_quantity', 18, 2)->default(0)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'partially_received', 'completed', 'cancelled'])->default('pending');
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
        Schema::dropIfExists('purchase_order_details');
    }

};
