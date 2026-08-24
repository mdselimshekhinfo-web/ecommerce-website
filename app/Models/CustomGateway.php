<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomGateway extends Model
{
    protected $fillable = [
        'gateway_type',
        'gateway_code',
        'display_name',
        'icon',
        'is_active',
        'is_sandbox',
        'credentials',
        'instructions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_sandbox' => 'boolean',
        'credentials' => 'array',
    ];

    public function getCredential($key, $default = '')
    {
        return $this->credentials[$key] ?? $default;
    }
}
