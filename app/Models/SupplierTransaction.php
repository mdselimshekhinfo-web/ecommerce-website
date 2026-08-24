<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'type',
        'amount',
        'payment_method',
        'reference_no',
        'notes',
        'running_balance',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'running_balance' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
