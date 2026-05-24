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
            $table->integer('batch_size_qty')->nullable()->after('status');
            $table->string('packing_type')->nullable()->after('batch_size_qty');
            $table->integer('product_type')->nullable()->after('packing_type');
            $table->integer('pack_size')->nullable()->after('product_type');
            $table->integer('box_size')->nullable()->after('pack_size');
            $table->integer('no_of_boxes')->nullable()->after('box_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_voachers', function (Blueprint $table) {
            $table->dropColumn('batch_size_qty');
            $table->dropColumn('packing_type');
            $table->dropColumn('product_type');
            $table->dropColumn('pack_size');
            $table->dropColumn('box_size');
            $table->dropColumn('no_of_boxes');
        });
    }
};
