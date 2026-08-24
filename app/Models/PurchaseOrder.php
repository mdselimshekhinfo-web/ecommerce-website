<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'supplier_id',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'notes',
        'received_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'received_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    public static function generatePONumber()
    {
        $count = static::count() + 1;
        return 'PO-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
