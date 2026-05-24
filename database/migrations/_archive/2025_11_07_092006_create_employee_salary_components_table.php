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
        Schema::create('employee_salary_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revision_id')->nullable();
            $table->unsignedBigInteger('salary_component_id')->nullable();
            $table->decimal('old_amount', 18, 2)->nullable();
            $table->decimal('amount', 18, 2)->nullable();

            $table->foreign('revision_id')->references('id')->on('employee_salary_revisions')->onDelete('cascade');
            $table->foreign('salary_component_id')->references('id')->on('salary_components')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_components');
    }
};
