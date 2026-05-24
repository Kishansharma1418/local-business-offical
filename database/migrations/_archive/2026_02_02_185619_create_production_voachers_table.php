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
        Schema::create('production_voachers', function (Blueprint $table) {
            $table->id();
            $table->biginteger('store_issurance_id')->nullable();
            $table->biginteger('bom_master_id')->nullable();
            $table->string('batch_number')->nullable();
            $table->date('mfg_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity', 15, 2)->default(0)->nullable();
            $table->string('rate')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0)->nullable();
            $table->string('status')->default('1')->nullable();
            $table->string('material_requisition_no')->nullable();
            $table->string('line_clearance_given_by')->nullable();
            $table->string('raw_material_issued_on')->nullable();
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
        Schema::dropIfExists('production_voachers');
    }
};
