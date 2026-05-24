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
        Schema::table('production_voachers', function (Blueprint $table) {
            $table->integer('pack_config_id')->after('bom_master_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_voachers', function (Blueprint $table) {
             $table->dropColumn('pack_config_id');
        });
    }
};
