<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionFlowStart extends Model
{
    protected $table="production_flow_starts";

    protected $fillable=[
       'production_process_id',
           'production_voucher_id',
            'assign_team_id',
           'bom_master_id',
           'pack_config_id',
           'branch_id',
           'batch_number',
           'mfg_date',
           'expiry_date',
            'quantity',
           'rate',
            'total_amount',
           'batch_size_qty',
           'packing_type',
           'product_type',
           'pack_size',
           'box_size',  
           'no_of_boxes', 
           'status',
           'created_by',
           'updated_by',
           'action_type',
           'current_step',
           'stock_in_done'
    ];

    public function bomMaster()
    {
        return $this->belongsTo(BomMaster::class, 'bom_master_id');
    }

    public function flowItems()
    {
        return $this->hasMany(ProductionFlowStartItem::class, 'production_flow_start_id');
    }

    public function page15Logs()
    {
        return $this->hasMany(ProductionPage15Log::class);
    }
    
    public function finishedGoodTransfer()
    {
        return $this->hasOne(ProductionFinishedGoodTransfer::class);
    }
 
    public function syrupFilling() 
    {
        return $this->hasOne(SyrupFillingForm::class);
    }

    public function page16Reconciliations()
    {
        return $this->hasMany(ProductionPage16Reconciliation::class, 'production_flow_start_id');
    }

}
