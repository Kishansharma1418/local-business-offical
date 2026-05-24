<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('employee_id')->nullable(); 
            $table->enum('document_type', [
                'aadhaar', 'pan','voter_id', 'driving_license', 'offer_letter', 'AppointmentLetter', 
                'experience_letter', 'relieving_letter', 'resume', 'degree_certificates', 'salary_slips',
                'photo', 'other','academic'
            ])->nullable();
            $table->string('document_number')->nullable();
            $table->string('document_name')->nullable();
            $table->string('document_filepath1')->nullable();
            $table->string('document_filepath2')->nullable();   
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->dateTime('verifiedon')->nullable();
            $table->enum('status', ['Pending', 'Verified', 'Rejected', 'Expired'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};