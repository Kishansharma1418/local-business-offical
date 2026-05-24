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
        Schema::create('employe_banks', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('confirm_account_number')->nullable();
            $table->string('branch_address')->nullable();
            $table->string('bank_passbook')->nullable();
            $table->string('cheque')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_banks');
    }
};
