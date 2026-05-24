<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id(); // Auto increment primary key

            $table->string('code', 50)->unique()->nullable();
            $table->string('warehouse_name', 150)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->enum('warehouse_purpose', [
                'GeneralStorage','Quarantine','Testing','Dispatch','ColdStorage','Returns','Sampling'
            ])->nullable();
            $table->enum('material_type', [
                'RawMaterial','PackagingMaterial','FinishedGood','SemiFinishedGood','All'
            ])->nullable();
            $table->boolean('is_default_warehouse')->default(false);
            $table->string('address_line1', 255)->nullable();
            $table->string('address_line2', 255)->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->enum('temperature_controlled',['Yes','No'])->default('Yes');
            $table->decimal('temperature_range_min', 5, 2)->nullable();
            $table->decimal('temperature_range_max', 5, 2)->nullable();
            $table->string('storage_conditions', 255)->nullable();
            $table->boolean('is_active')->default(true);
              $table->softDeletes();
            
           $table->timestamps();

            // Foreign keys
            // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            // $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            // $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            // $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
        
    }
};
