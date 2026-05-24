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
            $table->decimal('batch_size_qty', 15, 4)->nullable()->after('packing_type');
            $table->string('product_type')->nullable()->after('batch_size_qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_batches', function (Blueprint $table) {
            $table->dropColumn('batch_size_qty');
            $table->dropColumn('product_type');
        });
    }
};
