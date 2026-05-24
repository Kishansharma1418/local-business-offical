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
        Schema::create('production_packing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_flow_start_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bom_master_id')->constrained()->cascadeOnDelete();

            $table->string('product_name')->nullable();
            $table->string('batch_no')->nullable();

            // Line Clearance
            $table->string('previous_product')->nullable();
            $table->string('previous_batch_no')->nullable();
            $table->date('line_clearance_date')->nullable();

            // Machine Details
            $table->date('packing_date')->nullable();
            $table->string('machine_id')->nullable();
            $table->string('machine_operator')->nullable();
            $table->string('bfr_temperature')->nullable();
            $table->string('sfr_temperature')->nullable();
            $table->string('duration')->nullable();
            $table->string('verified_by')->nullable();

            // Carton
            $table->string('carton_batch_no')->nullable();
            $table->date('carton_mfd')->nullable();
            $table->date('carton_exp')->nullable();
            $table->decimal('carton_mrp', 10, 2)->nullable();
            $table->date('carton_printed_date')->nullable();

            // Foil
            $table->string('foil_batch_no')->nullable();
            $table->date('foil_mfd')->nullable();
            $table->date('foil_exp')->nullable();
            $table->decimal('foil_mrp', 10, 2)->nullable();
            $table->date('foil_printed_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_packing_details');
    }
};
