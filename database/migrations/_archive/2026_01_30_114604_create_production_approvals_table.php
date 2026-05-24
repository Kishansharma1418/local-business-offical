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
       Schema::create('production_approvals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('production_id')->index();
            $table->foreign('production_id')
                ->references('id')->on('production_batches')
                ->onDelete('cascade');

            $table->unsignedBigInteger('approver_id')->index();
            $table->foreign('approver_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->string('approval_level')->nullable();

            $table->string('decision')->default('PENDING');
             

            $table->date('approval_date')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */ 
    public function down(): void
    {
        Schema::dropIfExists('production_approvals');
    }
};
