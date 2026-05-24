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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->integer('component_id')->nullable();
            $table->decimal('amount',18,2)->nullable();
            $table->decimal('percentage',5,2)->nullable();
            $table->date('effactive_from',5,2)->nullable();
            $table->date('effactive_to',5,2)->nullable();
            $table->enum('status', ['0', '1'])->default('0'); // 0 =inactive, 1 = active
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('employee_salaries');
    }
};
