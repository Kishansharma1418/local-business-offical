<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionFlowStep extends Model
{
    protected $table="production_flow_steps";

    protected $fillable = [
            'production_flow_start_id',
            'bom_master_id',
            'branch_id',
            'bom_type_id',
            'production_process_id',
            'step_number',
            'process_name',
            'step_status',
            'started_at',
            'completed_at',
            'created_by',
            'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
