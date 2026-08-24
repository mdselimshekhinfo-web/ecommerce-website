<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'phone_number',
        'order_id',
        'message',
        'gateway_name',
        'status',
        'response_data',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
