<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('departments', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->uuid('department_id')->unique(); // UUID
            $table->string('code', 20)->unique();
            $table->string('department_name', 100);
            $table->unsignedBigInteger('parent_department_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('department_head_id')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
             $table->softDeletes();
            // Optional foreign keys (if needed)
            // $table->foreign('parent_department_id')->references('id')->on('departments')->onDelete('set null');
            // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            // $table->foreign('department_head_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('departments');
         
    }
};
