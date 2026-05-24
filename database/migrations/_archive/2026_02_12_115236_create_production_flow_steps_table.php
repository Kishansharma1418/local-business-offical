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
        Schema::create('production_flow_steps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('production_flow_start_id')->nullable();
            $table->bigInteger('bom_master_id')->nullable();
            $table->bigInteger('bom_type_id')->nullable();
            $table->bigInteger('production_process_id')->nullable();
            $table->integer('step_number')->nullable();
            $table->string('process_name')->nullable();
            $table->string('step_status')->nullable();
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_flow_steps');
    }
};
