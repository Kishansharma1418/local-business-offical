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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finished_goods_id')->nullable();
            $table->text('composition')->nullable();
            $table->string('strength_specification')->nullable();
            $table->string('packing_type')->nullable();
            $table->string('pack_size')->nullable();
            $table->string('brand')->nullable();
            $table->string('country_origin')->nullable();
            $table->string('storage_condation')->nullable();
            $table->string('shelf_life_months')->nullable();
            $table->enum('status', ['0', '1'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
