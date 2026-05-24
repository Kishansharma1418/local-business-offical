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
        Schema::create('employee_salary_revisions', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->integer('salary_componet_id')->nullable();
            $table->decimal('old_salary_total',18,2)->nullable();
            $table->decimal('new_basic_salary',18,2)->nullable();
            $table->decimal('new_hra',18,2)->nullable();
            $table->decimal('new_allowance',18,2)->nullable();
            $table->decimal('new_salary_total',18,2)->nullable();
            $table->date('effective_from')->nullable();
            $table->text('revision_reason')->nullable();
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
        Schema::dropIfExists('employee_salary_revisions');
    }
};
