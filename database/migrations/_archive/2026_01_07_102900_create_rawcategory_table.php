<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rawcategory', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->unsignedBigInteger('parent_category_id')->nullable();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->boolean('status')->default(1);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('rawcategory');
    }
};
