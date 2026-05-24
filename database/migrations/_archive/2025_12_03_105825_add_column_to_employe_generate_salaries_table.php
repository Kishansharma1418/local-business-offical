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
        Schema::table('employe_generate_salaries', function (Blueprint $table) {
            $table->decimal('pf_amount', 15, 2)->default(0)->after('status');
            $table->decimal('esi_amount', 15, 2)->default(0)->after('pf_amount');
            $table->decimal('hra_amount', 15, 2)->default(0)->after('esi_amount');
            $table->decimal('conveyance_amount', 15, 2)->default(0)->after('hra_amount');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employe_generate_salaries', function (Blueprint $table) {
            $table->dropColumn('pf_amount');
            $table->dropColumn('esi_amount');
            $table->dropColumn('hra_amount');
            $table->dropColumn('conveyance_amount');
        });
    }
};
