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
       Schema::create('employee_overtimes', function (Blueprint $table) {

    $table->id();
    $table->unsignedBigInteger('employee_id');
    $table->date('date');
    $table->decimal('hours', 5,2)->nullable();
    $table->decimal('rate_per_hour',10,2)->nullable();
    $table->decimal('total_amount',10,2)->nullable();
    $table->text('remark')->nullable();
    $table->unsignedBigInteger('created_by')->nullable();
    $table->unsignedBigInteger('updated_by')->nullable();
    $table->softDeletes();
    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_overtimes');
    }
};