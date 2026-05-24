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
      Schema::create('capsule_fillings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('production_flow_start_id')->nullable();
            $table->unsignedBigInteger('bom_master_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            $table->string('product_name')->nullable();
            $table->string('batch_no')->nullable();

            // JSON rows
            $table->json('capsule_records')->nullable();

            // inspection
            $table->string('filled_capsules_inspected_by')->nullable();
            $table->date('inspection_date')->nullable();

            // weights
            $table->decimal('total_weight_filled_capsules',12,3)->nullable();
            $table->decimal('total_weight_rejected_capsules',12,3)->nullable();

            // signatures
            $table->string('production_chemist_signature')->nullable();
            $table->date('production_chemist_date')->nullable();

            $table->string('qa_chemist_signature')->nullable();
            $table->date('qa_chemist_date')->nullable();

            $table->timestamps();

            // indexes
            $table->index('production_flow_start_id');
            $table->index('product_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capsule_fillings');
    }
};
