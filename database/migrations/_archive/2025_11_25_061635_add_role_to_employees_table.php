<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('employees', function (Blueprint $table) {
        $table->enum('role', ['sales', 'other'])->default('other')->after('unique_id');
    });
}

public function down()
{
    Schema::table('employees', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}

};
