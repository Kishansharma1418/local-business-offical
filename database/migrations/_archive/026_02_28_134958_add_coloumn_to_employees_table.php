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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('pf_number', 18, 2)->nullable()->after('pf_aplicable');
            $table->string('esi_number', 18, 2)->nullable()->after('esi_aplicable');
            $table->string('fathers_name')->nullable()->after('full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('pf_number');
            $table->dropColumn('esi_number');
            $table->dropColumn('fathers_name');
        });
    }
};