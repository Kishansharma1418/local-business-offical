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
        Schema::create('employe_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('employe_name')->nullable();
            $table->string('title')->nullable();
            $table->longtext('description')->nullable();
             
              $table->date('start_date');
                $table->date('end_date');

            $table->enum('status', ['0', '1'])->default('0'); // 0 =inactive, 1 = active
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_holidays');
    }
};
