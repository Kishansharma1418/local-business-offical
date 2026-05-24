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
        Schema::create('production_voacher_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_voacher_id')->index()->nullable();
            $table->unsignedBigInteger('material_id')->index()->nullable();
            $table->biginteger('warehouse_id')->nullable();
            $table->decimal('base_quantity', 15, 2)->default(0)->nullable();
            $table->decimal('final_quantity', 15, 2)->default(0)->nullable();
            $table->string('uom')->nullable();
            $table->decimal('overage_percent', 15, 2)->default(0)->nullable();
            $table->string('status')->default('1')->nullable();
            $table->string('specfication')->nullable();
            $table->string('control_ref_no')->nullable();
            $table->string('analytical_report_no')->nullable();
            $table->string('weight_by')->nullable();
            $table->string('recevied_checked_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_voacher_items');
    }
};
