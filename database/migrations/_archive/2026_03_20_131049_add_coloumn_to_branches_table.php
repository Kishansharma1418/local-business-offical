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
        Schema::table('branches', function (Blueprint $table) {
           $table->string('policy_no')->nullable()->after('gst_number');
           $table->string('dl_no')->nullable()->after('policy_no');
           $table->string('cbn_no')->nullable()->after('dl_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['policy_no', 'dl_no', 'cbn_no']);
        });
    }
};
