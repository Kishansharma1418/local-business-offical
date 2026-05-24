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
        Schema::create('customer_product_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('finish_goods_id')->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->enum('discount_type', ['specific', 'overall'])->default('specific');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('finish_goods_id')->references('id')->on('finished_goods')->onDelete('cascade');
            $table->timestamps();
        });

    

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_product_discounts');
    }
};
