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
        Schema::create('production_processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id')->index()->nullable();
            $table->unsignedBigInteger('bom_master_id')->index()->nullable();
            $table->unsignedBigInteger('bom_type_id')->index()->nullable();
            $table->string('bom_type_name')->index()->nullable();
            $table->string('process_step')->nullable();
            $table->text('description')->nullable();
            $table->integer('sequence')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('production_processes');
    }
};
