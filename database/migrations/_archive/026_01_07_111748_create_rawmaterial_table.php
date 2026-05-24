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
        Schema::create('rawmaterial', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('hsn_code')->nullable();
            $table->unsignedBigInteger('raw_category_id')->nullable();
            $table->unsignedBigInteger('sub_rawcategory_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->longtext('description')->nullable();
            $table->integer('lead_time_days')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
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
        Schema::dropIfExists('rawmaterial');
    }
};
