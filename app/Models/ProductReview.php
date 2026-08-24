<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'reviewer_name',
        'reviewer_phone',
        'rating',
        'comment',
        'images',
        'is_verified_purchase',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
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
