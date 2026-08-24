<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_district',
        'delivery_address',
        'delivery_notes',
        'shipping_zone',
        'shipping_cost',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'total_amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'order_status',
        'verification_status',
        'voice_call_log',
        'courier_name',
        'tracking_code',
        'courier_consignment_id',
        'courier_label_url',
        'customer_risk_score',
        'risk_reason',
        'admin_notes',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
