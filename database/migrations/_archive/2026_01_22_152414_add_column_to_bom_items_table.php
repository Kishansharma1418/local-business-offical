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
        Schema::table('bom_items', function (Blueprint $table) {
            $table->decimal('overage', 15, 4)->default(0)->after('per_unit_qty');
            $table->string('warehouse_id')->nullable()->after('material_id');
            $table->bigInteger('created_by')->nullable()->after('status');
            $table->bigInteger('updated_by')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bom_items', function (Blueprint $table) {
            $table->dropColumn('overage');
            $table->dropColumn('warehouse_id');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
};
