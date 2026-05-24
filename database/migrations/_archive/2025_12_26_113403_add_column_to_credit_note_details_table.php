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
        Schema::table('credit_note_details', function (Blueprint $table) {
                $table->unsignedBigInteger('invoice_detail_id')
                    ->nullable()
                    ->after('credit_note_id');

                $table->boolean('is_manual_item')
                    ->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_note_details', function (Blueprint $table) {
            $table->dropColumn('invoice_detail_id');
            $table->dropColumn('is_manual_item');
        });
    }
};
