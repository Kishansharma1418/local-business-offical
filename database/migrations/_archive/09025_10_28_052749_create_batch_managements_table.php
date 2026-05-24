<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_management', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');       
            $table->string('batch_number', 100)->unique();
            $table->date('manufacturing_date');
            $table->date('expiry_date');
            $table->unsignedBigInteger('warehouse_id');   
            $table->decimal('available_quantity', 18, 2);
            $table->decimal('unit_cost', 18, 2);
            $table->decimal('base_price', 18, 2);
            $table->decimal('gst_percent', 5, 2);
            $table->decimal('mrp', 18, 2);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // Foreign keys (optional)
            // $table->foreign('product_id')->references('id')->on('product_master')->onDelete('cascade');
            // $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_management');
    }
};
