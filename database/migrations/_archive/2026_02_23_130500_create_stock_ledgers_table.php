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
        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->integer('issurance_id')->nullable();
            $table->integer('bom_master_id')->nullable();
            $table->integer('raw_materail_batch_id')->nullable();
            $table->integer('raw_materail_id')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('uom_id')->nullable();
            $table->string('type')->nullable();
            $table->integer('referance_id')->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ledgers');
    }
};
