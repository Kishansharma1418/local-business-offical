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
       Schema::create('production_finished_good_transfers', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('production_flow_start_id')->nullable();
            $table->bigInteger('bom_master_id')->nullable();
            $table->bigInteger('finished_good_id')->nullable();
            $table->decimal('finished_goods_qty',12,2)->nullable();
            $table->string('batch_yield')->nullable();

            $table->enum('requisition_sheet_rm',['Yes','No'])->nullable();
            $table->string('requisition_sheet_rm_file')->nullable();

            $table->enum('specimen_carton',['Yes','No'])->nullable();
            $table->string('specimen_carton_file')->nullable();

            $table->enum('specimen_printed_foil',['Yes','No'])->nullable();
            $table->string('specimen_printed_foil_file')->nullable();

            $table->enum('bulk_testing_report',['Yes','No'])->nullable();
            $table->string('bulk_testing_report_file')->nullable();

            $table->enum('in_process_checks',['Yes','No'])->nullable();
            $table->string('in_process_checks_file')->nullable();

            $table->enum('finished_product_report',['Yes','No'])->nullable();
            $table->string('finished_product_report_file')->nullable();

            $table->foreignId('verified_head_production_id')->nullable();
            $table->timestamp('verified_head_production_at')->nullable();

            $table->foreignId('verified_head_qc_id')->nullable();
            $table->timestamp('verified_head_qc_at')->nullable();

            $table->foreignId('verified_head_qa_id')->nullable();
            $table->timestamp('verified_head_qa_at')->nullable();

            $table->decimal('release_qty',12,2)->nullable();

            $table->foreignId('batch_released_by_qa_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_finished_good_transfers');
    }
};
