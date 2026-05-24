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
        Schema::table('employe_generate_salaries', function (Blueprint $table) {
            $table->decimal('last_month_adjustment', 15, 2)->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employe_generate_salaries', function (Blueprint $table) {
            $table->dropColumn('last_month_adjustment');
        });
    }
};
