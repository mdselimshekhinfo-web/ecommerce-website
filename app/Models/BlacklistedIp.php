<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistedIp extends Model
{
    protected $fillable = [
        'ip_address',
        'phone_number',
        'reason',
        'status',
    ];
}
