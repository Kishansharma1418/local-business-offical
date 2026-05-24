<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // SUB-YYYY-XXXX
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans');
            $table->decimal('amount', 10, 2);
            $table->string('upi_id'); // the UPI id that was used to request
            $table->string('upi_app')->nullable(); // which app user used (phonepe/gpay/paytm/other)
            $table->string('transaction_id')->nullable(); // UTR / reference no submitted by client
            $table->string('screenshot')->nullable(); // optional screenshot path
            $table->text('client_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->enum('status', ['initiated', 'pending_verification', 'verified', 'rejected'])
                ->default('initiated');
            $table->date('new_expiry_date')->nullable(); // what expiry becomes after verification
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
