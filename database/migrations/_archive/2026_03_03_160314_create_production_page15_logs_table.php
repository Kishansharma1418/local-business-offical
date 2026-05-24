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
        Schema::create('production_page15_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_flow_start_id')->constrained()->onDelete('cascade')->nullable();

            $table->string('product_name')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('previous_product')->nullable();
            $table->string('previous_product_batch_no')->nullable();

            // Leak Test
            $table->date('leak_date')->nullable();
            $table->time('leak_time')->nullable();
            $table->string('leak_done_by')->nullable();
            $table->string('leak_result')->nullable();
            $table->string('leak_verified_by')->nullable();
            $table->text('leak_remarks')->nullable();

            // Packing
            $table->date('packing_date')->nullable();
            $table->string('strip_checked_by')->nullable();
            $table->string('carton_packing_done_by')->nullable();
            $table->integer('packed_carton_count')->default(0);
            $table->integer('rejected_carton_count')->default(0);
            $table->string('packing_verified_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_page15_logs');
    }
};
