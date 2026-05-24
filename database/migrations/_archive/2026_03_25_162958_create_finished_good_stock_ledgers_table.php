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
        Schema::create('finished_good_stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->enum('transaction_type', [
                'Production',
                'Sale',
                'Transfer',
                'Return',
                'Adjustment'
            ]);
            $table->decimal('inward_qty', 10, 2)->default(0);
            $table->decimal('outward_qty', 10, 2)->default(0);
            $table->decimal('balance_qty', 10, 2)->default(0);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finished_good_stock_ledgers');
    }
};
