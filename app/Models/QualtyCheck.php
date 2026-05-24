<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualtyCheck extends Model
{
    protected $table="qualty_checks";

    protected $fillable = [
          'production_flow_start_id',
            'bom_master_id',
            'bom_type_id',
            'production_process_id',
            'step_number',
            'remarks',
           'status',
           'report_path',
           'checked_by',
    ];

    public function production()
    {
        return $this->belongsTo(ProductionFlowStart::class);
    }


    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
