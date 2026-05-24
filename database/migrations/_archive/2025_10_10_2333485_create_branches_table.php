<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {

            $table->id();
            $table->string('code', 20)->unique();
            $table->string('branch_name', 200);
            $table->enum('branch_type', ['Head Office', 'Regional Office', 'Warehouse', 'Factory', 'Export Division']);
            $table->string('address_line1', 200);
            $table->string('address_line2', 200)->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('pin_code', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('gst_number', 20)->nullable();
            $table->string('pan_number', 20)->nullable();
            $table->unsignedBigInteger('manager_employee_id')->nullable();
            $table->unsignedBigInteger('parent_branch_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
             $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
         
            $table->text('notes')->nullable();
            $table->softDeletes(); 

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
