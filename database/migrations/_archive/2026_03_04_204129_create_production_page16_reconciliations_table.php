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
        Schema::create('production_page16_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_flow_start_id')->nullable();
            $table->integer('bom_master_id')->nullable();

            $table->string('material_type')->nullable();

            $table->decimal('std_qty', 12,2)->nullable();
            $table->decimal('qty_issued', 12,2)->nullable();
            $table->decimal('additional_required', 12,2)->nullable();
            $table->decimal('total_issued', 12,2)->nullable();

            $table->decimal('packed_qty', 12,2)->nullable();
            $table->decimal('sample_qty', 12,2)->nullable();
            $table->decimal('specimen_qty', 12,2)->nullable();
            $table->decimal('total_packed', 12,2)->nullable();

            $table->decimal('rejection_qty', 12,2)->nullable();
            $table->decimal('total_consumed', 12,2)->nullable();
            $table->decimal('returned_qty', 12,2)->nullable();
            $table->decimal('final_total', 12,2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_page16_reconciliations');
    }
};
