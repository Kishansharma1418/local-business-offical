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
        Schema::table('capsule_form1', function (Blueprint $table) {
             $table->unsignedBigInteger('production_flow_start_id')->nullable();
            $table->bigInteger('bom_master_id')->nullable();
            $table->bigInteger('product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('capsule_form1', function (Blueprint $table) {
              $table->dropColumn('production_flow_start_id','bom_master_id','product_id');
        });
    }
};
