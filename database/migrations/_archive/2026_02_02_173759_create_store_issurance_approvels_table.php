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
        Schema::create('store_issurance_approvels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_issurance_id')->index()->nullable();
            $table->unsignedBigInteger('approver_id')->index()->nullable();
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
        Schema::dropIfExists('store_issurance_approvels');
    }
};
