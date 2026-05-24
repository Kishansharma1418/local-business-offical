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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('module_name')->nullable();
            $table->string('action')->nullable();
            $table->integer('record_id')->nullable();

            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable(); 
            $table->string('perform_ip')->nullable();
            $table->string('perform_device')->nullable();
            $table->enum('status', ['0', '1'])->default('0'); // 0 = failed, 1 = success
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
