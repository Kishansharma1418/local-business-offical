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
        Schema::create('bom_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bom_master_id')->index()->nullable();
            $table->unsignedBigInteger('material_id')->index()->nullable();
            $table->integer('quantity')->default(0)->nullable();
            $table->enum('item_type', ['TABLET', 'PACKING'])->nullable()->comment('Formulation or Packing');
            $table->decimal('per_unit_qty', 12, 6)->nullable();
            $table->enum('uom',['kg','gm','liter','ml','pcs'])->nullable();
            $table->enum('status',['0','1'])->default('1')->comment('0=Inactive,1=Active');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_items');
    }
};
