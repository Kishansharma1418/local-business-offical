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
        Schema::create('employee_asset', function (Blueprint $table) {
            $table->id();
        
            $table->string('name')->nullable();
            $table->string('code')->unique()->nullable();
        
            $table->string('asset_type')->index()->nullable();
            $table->string('serial_number')->index()->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
        
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        
            $table->string('imei_number')->nullable();
        
            $table->enum('status', [
                'available',
                'assigned',
                'under_maintenance',
                'inactive',
                'scrap'
            ])->default('available');
        
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */ 
    public function down(): void
    {
        Schema::dropIfExists('employee_asset');
    }
};