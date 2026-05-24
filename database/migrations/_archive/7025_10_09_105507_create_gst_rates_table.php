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
        Schema::create('gst_rates', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['CGST', 'SGST', 'IGST'])->nullable();
            $table->string('gst_rate_name')->nullable(); // e.g., Standard GST 5%
            $table->decimal('cgst_rate', 5, 2)->nullable();
            $table->decimal('sgst_rate', 5, 2)->nullable();
            $table->decimal('igst_rate', 5, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gst_rates');
    }
};
