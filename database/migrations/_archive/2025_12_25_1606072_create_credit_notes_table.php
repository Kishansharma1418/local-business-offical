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
       Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();

            $table->string('credit_note_number')->unique();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('sales_order_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('sales_person_id')->nullable(); 


            $table->date('credit_note_date')->nullable();
             $table->decimal('total_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('net_amount', 18, 2)->default(0);
 
            $table->enum('type', ['invoice', 'direct'])
                ->comment('invoice = against invoice, direct = without invoice');

            $table->enum('status', ['open', 'closed'])->default('open');
             $table->enum('payment_status', [
                'Pending', 'Partial', 'Paid'
            ])->default('Pending');
               $table->string('reference_number')->nullable();
            $table->string('reason_type')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_notes');
    }
};
