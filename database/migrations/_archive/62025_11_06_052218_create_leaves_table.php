<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->enum('leave_category', ['medical', 'casual', 'paid', 'work from home'])->nullable();
            $table->enum('leave_type', ['half day', 'full day'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('total_days')->default(0);
            $table->enum('status', ['Pending', 'Verified', 'Rejected', 'Expired'])->default('Pending');
            $table->text('description')->nullable();
             $table->text('reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
