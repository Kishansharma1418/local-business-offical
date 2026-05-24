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
        Schema::create('apply_credit_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_note_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->decimal('applied_amount', 15, 2)->nullable();
            $table->decimal('remaining_amount', 15, 2)->nullable();
            $table->decimal('credit_note_balance', 15, 2)->nullable();
            $table->decimal('invoice_balance', 15, 2)->nullable();
            $table->decimal('over_applied_amount', 15, 2)->default(0)->nullable();
            $table->decimal('under_applied_amount', 15, 2)->default(0)->nullable();
            $table->date('applied_date')->nullable();
            $table->string('status')->default('applied')->nullable();
            $table->foreign('credit_note_id')->references('id')->on('credit_notes')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoice_orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apply_credit_invoices');
    }
};
