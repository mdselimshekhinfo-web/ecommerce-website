<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'name_bn',
        'slug',
        'sku',
        'short_description',
        'short_description_bn',
        'description',
        'description_bn',
        'price',
        'cost_price',
        'sale_price',
        'stock_quantity',
        'thumbnail',
        'images',
        'variants',
        'specs',
        'badge',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'seo_score',
        'is_featured',
        'is_trending',
        'is_flash_deal',
        'flash_deal_end',
        'rating',
        'reviews_count',
        'sales_count',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'variants' => 'array',
        'specs' => 'array',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_flash_deal' => 'boolean',
        'flash_deal_end' => 'datetime',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(5);
            }
            if (empty($product->sku)) {
                $product->sku = 'NX-' . strtoupper(Str::random(6));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }

    public function getEffectivePriceAttribute()
    {
        return ($this->sale_price && $this->sale_price < $this->price) ? $this->sale_price : $this->price;
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }
}
