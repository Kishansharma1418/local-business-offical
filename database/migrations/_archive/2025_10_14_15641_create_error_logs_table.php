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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('module_name')->nullable();
            $table->string('error_code')->nullable();
            $table->text('error_msg')->nullable();
            $table->string('function_name')->nullable();
            $table->string('request_ip')->nullable();
            $table->string('device_info')->nullable();
            $table->string('error_url')->nullable();
            $table->string('picture')->nullable();
            $table->integer('record_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->enum('status', ['0', '1','2','3'])->default('0'); // 0 = open, 1 = resolved,2=in-progress,3=ignored
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
