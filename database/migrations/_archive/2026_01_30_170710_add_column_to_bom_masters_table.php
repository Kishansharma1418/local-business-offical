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
        Schema::table('bom_masters', function (Blueprint $table) {
            $table->string('packing_type')->nullable()->after('status');
            $table->string('pack_size')->nullable()->after('status');
            $table->string('box_size')->nullable()->after('status');
            $table->string('no_of_boxes')->nullable()->after('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bom_masters', function (Blueprint $table) {
            $table->dropColumn('packing_type');
            $table->dropColumn('pack_size');
            $table->dropColumn('box_size');
            $table->dropColumn('no_of_boxes');
        });
    }
};
