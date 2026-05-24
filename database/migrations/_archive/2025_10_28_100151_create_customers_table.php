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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->enum('customer_type', ['Doctor','Chemist','Distributor','Stockist','Hospital','Other'])->nullable();
            $table->string('contact_person')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->decimal('credit_limit',18,2)->nullable();
            $table->string('credit_days')->nullable();
            $table->enum('is_blocked', ['0', '1'])->default('0'); // 0 =no, 1 = yes
            $table->text('blocked_reason')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->enum('is_login', ['0', '1'])->default('0'); // 0 =no, 1 = yes
            $table->enum('status', ['0', '1'])->default('0'); // 0 =inactive, 1 = active
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
