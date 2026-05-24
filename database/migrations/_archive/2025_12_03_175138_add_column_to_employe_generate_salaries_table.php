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
            $table->decimal('tds_amount', 15, 2)->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employe_generate_salaries', function (Blueprint $table) {
            $table->dropColumn('tds_amount');
        });
    }
};
