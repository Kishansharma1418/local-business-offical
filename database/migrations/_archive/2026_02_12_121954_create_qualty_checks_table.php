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
        Schema::create('qualty_checks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('production_flow_start_id')->nullable();
            $table->bigInteger('bom_master_id')->nullable();
            $table->bigInteger('bom_type_id')->nullable();
            $table->bigInteger('production_process_id')->nullable();
            $table->integer('step_number')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable();
            $table->string('report_path')->nullable();
            $table->bigInteger('checked_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualty_checks');
    }
};
