<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brokers', function (Blueprint $table) {
                  $table->id();
            $table->string('code', 50)->unique(); 
            $table->string('broker_name', 255); 
            $table->string('contact_person', 150)->nullable();
            $table->string('mobile_no', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('gst_number', 20)->nullable();
            $table->string('pan_number', 20);
            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('country_id');
            $table->string('pincode', 10);
            $table->enum('commission_type', ['Percentage', 'Fixed']);
            $table->decimal('commission_value', 10, 2);
            $table->unsignedInteger('currency_id')->nullable();
             $table->enum('status', [
              'Active',
              'Inactive'
            ])->default('Active');
            $table->string('remarks', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brokers');
    }
};
