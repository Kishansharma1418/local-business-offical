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
        Schema::create('bom_masters', function (Blueprint $table) {
            $table->id();
            $table->string('bom_number')->unique()->index()->nullable();
            $table->string('bom_version')->unique()->index()->nullable();
            $table->string('batch_size')->unique()->index()->nullable();
            $table->date('bom_date')->nullable();
            $table->unsignedBigInteger('finished_good_id')->index()->nullable();
            $table->integer('quantity')->default(0)->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status',['0','1'])->default('1')->comment('0=Inactive,1=Active');
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
        Schema::dropIfExists('bom_masters');
    }
};
