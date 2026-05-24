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
      Schema::create('production_process_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_process_id')->index()->nullable();
            $table->unsignedBigInteger('bom_item_id')->index()->nullable();
            $table->decimal('qty', 10, 3)->nullable();
            $table->string('uom')->nullable();
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
        Schema::dropIfExists('production_process_items');
    }
};
