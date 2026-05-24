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
        Schema::create('coated_tablet_production_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_batch_id')->nullable();
            $table->unsignedBigInteger('bom_master_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->unsignedBigInteger('production_flow_id')->nullable();
            $table->string('product_name')->nullable();
            $table->json('thickness')->nullable();
            $table->json('weight')->nullable();
            $table->json('hardness')->nullable();

            $table->decimal('average_thickness',8,2)->nullable();
            $table->decimal('average_weight',8,2)->nullable();
            $table->decimal('average_hardness',8,2)->nullable();

            $table->date('tablets_inspected_date')->nullable();

            $table->decimal('total_weight_coated_tablets',10,2)->nullable();
            $table->decimal('total_weight_rejected_tablets',10,2)->nullable();
            

            $table->string('production_chemist')->nullable();
            $table->date('production_date')->nullable();

            $table->string('qa_incharge')->nullable();
            $table->date('qa_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coated_tablet_production_forms');
    }
};
