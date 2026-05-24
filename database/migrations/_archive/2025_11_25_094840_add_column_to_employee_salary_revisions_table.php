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
        Schema::table('employee_salary_revisions', function (Blueprint $table) {
            
            $table->decimal('net_salary',18,2)->nullable()->after('new_allowance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_salary_revisions', function (Blueprint $table) {
            
            $table->dropColumn('net_salary');
        });
    }
};
