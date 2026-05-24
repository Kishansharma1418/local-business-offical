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
        Schema::create('purchase_order_approvels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id')->index()->nullable();
            $table->unsignedBigInteger('approver_id')->index()->nullable();
            $table->string('status')->default('pending')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('created_by')->index()->nullable();
            $table->unsignedBigInteger('updated_by')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_approvels');
    }
};
