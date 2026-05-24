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
            $table->decimal('pf_company_contribution', 15, 2)->nullable()->after('pf_amount');
             $table->decimal('esi_company_contribution', 15, 2)->nullable()->after('esi_amount');
              $table->decimal('total_company_contribution', 15, 2)->nullable()->after('esi_company_contribution');
              $table->decimal('total_deduction', 15, 2)->nullable()->after('tds_amount');
               $table->decimal('total_earning', 15, 2)->nullable()->after('gross_salary');
               $table->decimal('bounnce_employee', 15, 2)->nullable()->after('total_earning');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employe_generate_salaries', function (Blueprint $table) {
            $table->dropColumn('pf_company_contribution');
            $table->dropColumn('esi_company_contribution');
            $table->dropColumn('total_company_contribution');
            $table->dropColumn('total_deduction');
            $table->dropColumn('total_earning');
            $table->dropColumn('bounnce_employee');
        });
    }
};
