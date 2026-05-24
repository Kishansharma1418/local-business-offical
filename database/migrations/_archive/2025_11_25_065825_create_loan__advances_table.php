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
        Schema::create('loan_advances', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->integer('loan_amount');
            $table->string('month');
            $table->date('start_month');
            $table->integer('deduction_amount');
            $table->enum('status', ['Active','Inactive'])->default('Active');
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
        Schema::dropIfExists('loan_advances');
    }
};
