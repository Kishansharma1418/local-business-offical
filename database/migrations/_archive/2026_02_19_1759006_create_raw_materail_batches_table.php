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
        Schema::create('raw_materail_batches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('raw_material_id')->nullable();
            $table->bigInteger('purchase_order_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('referance_no')->nullable();
            $table->string('grn_no')->nullable();
            $table->string('analytic_report_no')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materail_batches');
    }
};
