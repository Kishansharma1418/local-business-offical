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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('official_mail')->nullable()->nullable();
            $table->string('personal_mail')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('alternative_no')->nullable();
            $table->date('joining_date')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('designation_id')->nullable();
            $table->integer('reporting_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->integer('territory_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->text('address_line1')->nullable();
            $table->text('address_line2')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('pincode')->nullable();
            $table->enum('marital_status', ['Single','Married','Other'])->nullable();
            $table->string('blood_group')->nullable();
            $table->string('emergancy_contact_name')->nullable();
            $table->string('emergancy_contact_number')->nullable();
            $table->enum('employee_type', ['Permanent','Contract','Intern','Consultant'])->nullable();

            $table->date('relieving_date')->nullable();
            $table->enum('separation_type', ['Resignation','Termination','Absconding'])->nullable();
            $table->text('separation_remarks')->nullable();
            $table->text('relieving_approved_by')->nullable();
            $table->date('relieving_approvel_date')->nullable();

            $table->string('pan_no')->nullable();
            $table->string('aadhaar_no')->nullable();
            $table->string('uan_no')->nullable();
            $table->enum('is_login', ['0', '1'])->default('0'); // 0 =no, 1 = yes
            $table->enum('status', ['0', '1'])->default('0'); // 0 =inactive, 1 = active
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
