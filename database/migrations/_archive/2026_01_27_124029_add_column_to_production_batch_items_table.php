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
        Schema::table('production_batch_items', function (Blueprint $table) {
            $table->string('specfication')->after('final_quantity')->nullable();
            $table->string('control_ref_no')->after('final_quantity')->nullable();
            $table->string('analytical_report_no')->after('final_quantity')->nullable();
            $table->string('weight_by')->after('final_quantity')->nullable();
            $table->string('recevied_checked_by')->after('final_quantity')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_batch_items', function (Blueprint $table) {
            $table->dropColumn(['specfication', 'control_ref_no', 'analytical_report_no', 'weight_by', 'recevied_checked_by']);
        });
    }
};
