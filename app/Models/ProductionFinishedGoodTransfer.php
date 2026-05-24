<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionFinishedGoodTransfer extends Model
{
     
     protected $table = 'production_finished_good_transfers';

     protected $fillable = [
        'production_flow_start_id',
        'bom_master_id',
        'finished_good_id',
        'finished_goods_qty',
        'batch_yield',
        'requisition_sheet_rm',
        'requisition_sheet_rm_file',
        'specimen_carton',
        'specimen_carton_file',
        'specimen_printed_foil',
        'specimen_printed_foil_file',
        'bulk_testing_report',
        'bulk_testing_report_file',
        'in_process_checks',
        'in_process_checks_file',
        'finished_product_report',
        'finished_product_report_file',
        'verified_head_production',
        'verified_head_qc',
        'verified_head_qa',
        'if_any',
        'if_any_file',
        'analytic_report_no',
        'analytic_report_date',
        'analytic_report_no_file',
        'verified_head_production_id',
        'verified_head_production_at',
        'verified_head_qc_id',
        'verified_head_qc_at',
        'verified_head_qa_id',
        'verified_head_qa_at',
        'release_qty',
     ];
}
