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
        Schema::table('syrup_filling_forms', function (Blueprint $table) {
            $table->string('temprature')->nullable();
            $table->string('colour_appearance')->nullable();
            $table->string('ph')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('syrup_filling_forms', function (Blueprint $table) {
        $table->dropColoum(['temprature','colour_appearance','ph']);
        });
    }
};
