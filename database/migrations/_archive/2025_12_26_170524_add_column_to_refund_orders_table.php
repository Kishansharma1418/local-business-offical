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
        Schema::table('refund_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_order_id')->nullable()->after('credit_note_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund_orders', function (Blueprint $table) {
            $table->dropColumn('invoice_order_id');
        });
    }
};
