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
        Schema::table('production_batches', function (Blueprint $table) {
            $table->string('material_requisition_no')->after('quantity')->nullable();
            $table->string('line_clearance_given_by')->after('material_requisition_no')->nullable();
            $table->string('raw_material_issued_on')->after('material_requisition_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_batches', function (Blueprint $table) {
            $table->dropColumn(['material_requisition_no', 'line_clearance_given_by', 'raw_material_issued_on']);
        });
    }
};
