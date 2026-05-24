<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('sales_order_approval', function (Blueprint $table) {
            $table->id();
             $table->string('name')->nullable();
            $table->unsignedBigInteger('sales_order_id')->nullable();
            $table->unsignedBigInteger('approved_id')->nullable();
            $table->enum('approval_status', [
                'Pending', 'Approved', 'Rejected'
            ])->default('Pending');
            $table->text('remark')->nullable();
             $table->dateTime('action_date')->nullable();
          $table->unsignedBigInteger('approval_level')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_order_approval');
    }
};