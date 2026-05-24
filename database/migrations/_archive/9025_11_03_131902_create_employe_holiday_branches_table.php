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
        Schema::create('employe_holiday_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employe_holiday_id');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('employe_holiday_id')->references('id')->on('employe_holidays')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_holiday_branches');
    }
};
