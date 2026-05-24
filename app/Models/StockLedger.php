<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $fillable=[
         'issurance_id',
         'bom_master_id',
          'raw_materail_batch_id',
          'raw_materail_id',
           'qty',
          'uom_id',
           'type',
            'referance_id',
           'approved_by',
    ];
  
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_materail_id');
    }
    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }
    public function referance()
    {
        return $this->belongsTo(User::class, 'referance_id');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
