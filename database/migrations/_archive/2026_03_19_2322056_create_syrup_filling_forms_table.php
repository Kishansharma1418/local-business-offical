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
        Schema::create('syrup_filling_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_flow_start_id')->nullable();
            $table->bigInteger('bom_master_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('batch_number')->nullable();
            $table->json('datetime')->nullable();
            $table->json('filled_volume')->nullable();
            $table->json('ropp_cap')->nullable();
            $table->json('checked_by')->nullable();
            $table->json('verified_by')->nullable();
            $table->integer('total_filled_qty')->nullable();
            $table->string('prev_product')->nullable();
            $table->string('prev_batch')->nullable();
            $table->string('line_clearance_by')->nullable();

            $table->string('inspection_start')->nullable();
            $table->string('inspection_done_by')->nullable();
            $table->string('inspection_completed')->nullable();
            $table->string('inspection_verified')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syrup_filling_forms');
    }
};
