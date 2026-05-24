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
        Schema::create('capsule_equipment_cleanings', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('capsule_form1_id')->nullable();
            $table->unsignedBigInteger('production_flow_start_id')->nullable();
            $table->bigInteger('bom_master_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('equipment_name')->nullable();
            $table->string('equipment_id')->nullable();
            $table->string('previous_product_name')->nullable();
            $table->string('previous_batch_no')->nullable();
            $table->string('cleaned_by')->nullable();
            $table->date('cleaned_date')->nullable();
            $table->string('verified_by')->nullable();
            $table->date('verified_date')->nullable();
            $table->string('line_clierence_given_by')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capsule_cleaning_form');
    }
};
