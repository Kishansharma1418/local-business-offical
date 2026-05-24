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
        Schema::table('purchase_order_details', function (Blueprint $table) {
                $table->decimal('qa_received_qty', 10, 2)->nullable()->after('status');
                $table->foreignId('qa_uom_id')->nullable()->constrained('uoms')->after('qa_received_qty');
                $table->string('qa_status')->nullable()->after('qa_uom_id');
                $table->string('qa_report_file')->nullable()->after('qa_status');
                $table->string('analysis_report_no')->nullable()->after('qa_report_file');
                $table->text('qa_remarks')->nullable()->after('analysis_report_no');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_details', function (Blueprint $table) {
             $table->dropColumn('qa_received_qty');
                $table->dropColumn('qa_uom_id');
                $table->dropColumn('qa_status');
                $table->dropColumn('qa_report_file');
                $table->dropColumn('analysis_report_no');
                $table->dropColumn('qa_remarks');
        });
    }
};
  