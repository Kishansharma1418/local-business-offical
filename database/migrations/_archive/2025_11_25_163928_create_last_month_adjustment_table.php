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
        Schema::create('last_month_adjustment', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('adjustment_date')->nullable();
            $table->string('adjustment_month')->nullable();
            $table->string('current_month')->nullable();
            $table->decimal('adjustment_amount',15,2)->default(0);
            $table->text('description')->nullable();
            $table->enum('status',['Debit','Credit'])->default('Debit');
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
        Schema::dropIfExists('last_month_adjustment');
    }
};
