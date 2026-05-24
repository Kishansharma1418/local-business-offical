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
        Schema::table('finished_goods', function (Blueprint $table) {
            $table->decimal('unit_cost', 18, 2)->nullable()->after('status');
            $table->decimal('base_price', 18, 2)->nullable()->after('unit_cost');
            $table->decimal('gst_percent', 5, 2)->nullable()->after('base_price');
            $table->decimal('mrp', 18, 2)->nullable()->after('gst_percent'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finished_goods', function (Blueprint $table) {
            $table->dropColumn('unit_cost');
            $table->dropColumn('base_price');
            $table->dropColumn('gst_percent');
            $table->dropColumn('mrp');
        });
    }
};
