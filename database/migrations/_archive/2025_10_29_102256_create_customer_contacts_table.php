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
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->enum('is_default', ['0', '1'])->default('0');
             $table->softDeletes(); // 0 =no, 1 = yes
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_contacts');
    }
};
