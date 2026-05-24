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
        Schema::table('production_finished_good_transfers', function (Blueprint $table) {
             $table->enum('if_any',['Yes','No'])->nullable();
            $table->string('if_any_file')->nullable();
            $table->string('analytic_report_no')->nullable();
            $table->string('analytic_report_date')->nullable();
            $table->string('analytic_report_no_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_finished_good_transfers', function (Blueprint $table) {
          $table->dropColumn(['if_any', 'if_any_file', 'analytic_report_no','analytic_report_date','analytic_report_no_file']);
        });
    }
};
