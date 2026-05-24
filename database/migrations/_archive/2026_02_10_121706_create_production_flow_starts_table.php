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
        Schema::create('production_flow_starts', function (Blueprint $table) {
            $table->id();
            $table->biginteger('production_process_id')->nullable();
            $table->biginteger('production_voucher_id')->nullable();
            $table->text('assign_team_id')->nullable();
            $table->biginteger('bom_master_id')->nullable();
            $table->string('batch_number')->nullable();
            $table->date('mfg_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity', 15, 2)->default(0)->nullable();
            $table->string('rate')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0)->nullable();
            $table->integer('batch_size_qty')->nullable();
            $table->string('packing_type')->nullable();
            $table->integer('product_type')->nullable();
            $table->integer('pack_size')->nullable();
            $table->integer('box_size')->nullable();  
            $table->integer('no_of_boxes')->nullable(); 
            $table->string('status')->default('1')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_flow_starts');
    }
};
