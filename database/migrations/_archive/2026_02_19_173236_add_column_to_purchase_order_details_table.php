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
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->date('mfg_date')->nullable()->after('batch_no');
            $table->date('expiry_date')->nullable()->after('batch_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->dropColumn('mfg_date');
            $table->dropColumn('expiry_date');
        });
    }
};
