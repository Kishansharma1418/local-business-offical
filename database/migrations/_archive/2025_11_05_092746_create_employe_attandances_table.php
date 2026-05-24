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
        Schema::create('employe_attandances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->date('date')->nullable();
            $table->enum('status', ['Present', 'Absent', 'Leave', 'Holiday','Half Day','Weekly Off'])->default('Present');
            $table->boolean('is_holiday')->default(false);
            $table->string('holiday_name')->nullable(); 
            $table->string('hours_work')->nullable(); 
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('remarks')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_attandances');
    }
};
