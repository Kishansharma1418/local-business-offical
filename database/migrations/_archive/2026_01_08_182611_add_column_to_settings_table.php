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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('dl_no_1')->nullable()->after('company_address');
            $table->string('dl_no_2')->nullable()->after('dl_no_1');
            $table->string('cbn_registration_no')->nullable()->after('dl_no_2');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['dl_no_1', 'dl_no_2', 'cbn_registration_no']);
        });
    }
};
