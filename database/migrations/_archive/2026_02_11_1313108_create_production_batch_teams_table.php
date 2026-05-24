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
         Schema::create('production_batch_teams', function (Blueprint $table) {

            $table->id();

           $table->foreignId('production_batch_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();


            $table->foreignId('bom_master_id')
                     ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('bom_type_id')
                    ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('role_id')
                    ->nullable()
                  ->constrained('roles')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                    ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('module_type')->nullable();

            $table->unsignedBigInteger('module_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();

            $table->timestamps();

          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batch_teams');
    }
};
