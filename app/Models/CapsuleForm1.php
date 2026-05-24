<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleForm1 extends Model
{
    use HasFactory;

    protected $table = 'capsule_form1';

    protected $fillable = [
        'production_flow_start_id',
        'bom_master_id',
        'product_id',
       
        'document_no',
        'mfg_license_no',
        'generic_name',
        'product_name',
        'composition',

        'master_formula_record_no',
        'batch_no',
        'shelf_life',

        'mfg_date',
        'exp_date',

        'batch_size',
        'unit_packing',

        'batch_commenced_on',
        'batch_completed_on',

        'issued_by',
        'issued_date',

        'received_by',
        'received_date',

        'prepared_by',
        'prepared_date',

        'reviewed_by',
        'reviewed_date',

        'approved_by',
        'approved_date',
    ];

    protected $casts = [
        'mfg_date' => 'date',
        'exp_date' => 'date',
        'batch_commenced_on' => 'date',
        'batch_completed_on' => 'date',
        'issued_date' => 'date',
        'received_date' => 'date',
        'prepared_date' => 'date',
        'reviewed_date' => 'date',
        'approved_date' => 'date',
    ];
}
