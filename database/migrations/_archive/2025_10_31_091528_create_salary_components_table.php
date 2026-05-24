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
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('component_name')->nullable();
            $table->enum('component_type',['Earning','Deduction'])->nullable();
            $table->enum('calculation_type',['Fixed','Percentage'])->nullable();
            $table->integer('based_component_id')->nullable();
            $table->enum('is_taxable', ['0', '1'])->default('0'); // 0 =no, 1 = yes
            $table->enum('status', ['0', '1'])->default('0'); // 0 =inactive, 1 = active
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_components');
    }
};
