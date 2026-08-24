<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'items',
        'subtotal',
        'recovery_status',
        'recovery_notes',
        'last_active_at',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'last_active_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
