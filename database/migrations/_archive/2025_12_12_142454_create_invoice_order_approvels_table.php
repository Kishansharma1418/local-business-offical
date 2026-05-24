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
        Schema::create('invoice_order_approvels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_order_id')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->enum('status', ['Approved', 'Rejected'])->default('Approved');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('invoice_order_approvels');
    }
};
