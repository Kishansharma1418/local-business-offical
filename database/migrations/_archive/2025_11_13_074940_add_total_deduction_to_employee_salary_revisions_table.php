<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('employee_salary_revisions', function (Blueprint $table) {
        $table->decimal('total_deduction', 10, 2)->nullable()->after('new_salary_total');
    });
}

public function down()
{
    Schema::table('employee_salary_revisions', function (Blueprint $table) {
        $table->dropColumn('total_deduction');
    });
}

};
