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
        Schema::create('compression_ipqc_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_flow_id')->nullable();
            $table->unsignedBigInteger('bom_master_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name', 255)->nullable();
            $table->string('batch_no')->nullable();
            $table->json('datetime')->nullable();
            $table->json('weight20')->nullable();
            $table->json('dt')->nullable();
            $table->json('hardness')->nullable();
            $table->json('friability')->nullable();
            $table->json('thickness')->nullable();
            $table->json('sign_date')->nullable();
            $table->json('remarks')->nullable();
            $table->json('inspected_by',8,2)->nullable();
            $table->decimal('total_weight_uncoated',8,2)->nullable();
            $table->decimal('total_weight_rejected',8,2)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compression_ipqc_records');
    }
};
