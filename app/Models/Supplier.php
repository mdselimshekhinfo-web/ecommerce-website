<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'phone',
        'email',
        'address',
        'opening_balance',
        'total_purchased',
        'total_paid',
        'current_due',
        'status',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'total_purchased' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'current_due' => 'decimal:2',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function transactions()
    {
        return $this->hasMany(SupplierTransaction::class)->orderBy('created_at', 'desc');
    }

    public function recalculateBalances()
    {
        $totalPurchased = $this->purchaseOrders()->where('status', '!=', 'cancelled')->sum('total_amount');
        $totalPaid = $this->transactions()->where('type', 'payment')->sum('amount');
        $currentDue = ($this->opening_balance + $totalPurchased) - $totalPaid;

        $this->update([
            'total_purchased' => $totalPurchased,
            'total_paid' => $totalPaid,
            'current_due' => max(0, $currentDue),
        ]);
    }
}
