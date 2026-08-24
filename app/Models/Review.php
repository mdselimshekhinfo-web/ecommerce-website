<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'customer_location',
        'rating',
        'comment',
        'is_verified_purchase',
        'status',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'status' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
