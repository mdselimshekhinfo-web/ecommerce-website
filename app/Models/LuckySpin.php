<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuckySpin extends Model
{
    protected $fillable = [
        'ip_address',
        'user_id',
        'prize_won',
        'coupon_code',
    ];
}
