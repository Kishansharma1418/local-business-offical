<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishedGoodStockLedger extends Model
{
    protected $fillable = [
        'date',
        'product_id',
        'batch_id',
        'transaction_type',
        'inward_qty',
        'outward_qty',
        'balance_qty',
        'reference_id',
        'created_by',
        'updated_by',
    ];


    public static function addEntry(array $data): self
    {
        // Last balance lao is batch ka
        $lastEntry = self::where('batch_id', $data['batch_id'])
            ->latest('date')
            ->latest('id')
            ->first();

        $lastBalance = $lastEntry ? $lastEntry->balance_qty : 0;

        $inward  = $data['inward_qty']  ?? 0;
        $outward = $data['outward_qty'] ?? 0;

        $data['balance_qty'] = $lastBalance + $inward - $outward;
        $data['created_by']  = auth()->id();

        return self::create($data);
    }


    public function product()
    {
        return $this->belongsTo(FinishedGood::class, 'product_id');
    }
    public function batch()
    {
        return $this->belongsTo(BatchManagement::class, 'batch_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
