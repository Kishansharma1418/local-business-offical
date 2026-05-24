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
        Schema::create('production_batch_items', function (Blueprint $table) {
            $table->id();
            $table->biginteger('production_batch_id')->nullable();
            $table->biginteger('material_id')->nullable();
            $table->biginteger('warehouse_id')->nullable();
            $table->decimal('base_quantity', 15, 2)->default(0)->nullable();
            $table->decimal('final_quantity', 15, 2)->default(0)->nullable();
            $table->string('uom')->nullable();
            $table->decimal('overage_percent', 15, 2)->default(0)->nullable();
            $table->string('status')->default('1')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batch_items');
    }
};
