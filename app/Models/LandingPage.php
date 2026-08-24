<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'product_id',
        'headline',
        'subheadline',
        'offer_price',
        'regular_price',
        'video_url',
        'banner_image',
        'features_list',
        'countdown_end_time',
        'custom_css',
        'status',
    ];

    protected $casts = [
        'features_list' => 'array',
        'offer_price' => 'decimal:2',
        'regular_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
