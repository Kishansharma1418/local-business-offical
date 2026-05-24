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
        Schema::create('capsule_form1', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('document_no')->nullable();
            $table->string('mfg_license_no')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('product_name')->nullable();

            // Composition
            $table->text('composition')->nullable();

            // Batch Details
            $table->string('master_formula_record_no')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('shelf_life')->nullable();

            $table->date('mfg_date')->nullable();
            $table->date('exp_date')->nullable();

            $table->string('batch_size')->nullable();
            $table->string('unit_packing')->nullable();

            $table->date('batch_commenced_on')->nullable();
            $table->date('batch_completed_on')->nullable();

            // Issued / Received
            $table->string('issued_by')->nullable();
            $table->date('issued_date')->nullable();

            $table->string('received_by')->nullable();
            $table->date('received_date')->nullable();

            // Document Sign Section
            $table->string('prepared_by')->nullable();
            $table->date('prepared_date')->nullable();

            $table->string('reviewed_by')->nullable();
            $table->date('reviewed_date')->nullable();

            $table->string('approved_by')->nullable();
            $table->date('approved_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capsule_form1');
    }
};
