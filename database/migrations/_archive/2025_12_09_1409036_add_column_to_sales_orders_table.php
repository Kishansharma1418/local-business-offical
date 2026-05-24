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
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->decimal('overall_bill_discount_amount', 18, 2)->nullable()->after('net_amount');
            $table->decimal('overall_bill_discount_percent', 5, 2)->nullable()->after('overall_bill_discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('overall_bill_discount_amount');
            $table->dropColumn('overall_bill_discount_percent');
        });
    }
};
