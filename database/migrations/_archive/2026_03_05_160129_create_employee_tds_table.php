<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_tds', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('employee_id');

            $table->string('financial_year');

            $table->string('month');

            $table->decimal('gross_salary',10,2)->nullable();

            $table->decimal('taxable_salary',10,2)->nullable();

            $table->decimal('tds_percent',5,2)->nullable();

            $table->decimal('tds_amount',10,2);

            $table->text('remark')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_tds');
    }
};