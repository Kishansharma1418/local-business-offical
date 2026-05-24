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
            $table->unsignedBigInteger('verified_by_production')->nullable();
            $table->text('verified_notes_production')->nullable()->after('verified_by_production');
            $table->foreign('verified_by_production')->references('id')->on('users')->onDelete('set null')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_voachers', function (Blueprint $table) {
            $table->dropColumn('verified_by_production');
            $table->dropColumn('verified_notes_production');
        });
    }
};
