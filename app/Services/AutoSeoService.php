<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Helpers\BanglaHelper;
use Illuminate\Support\Str;

class AutoSeoService
{
    /**
     * Generate Google-optimized SEO tags for a single product
     */
    public static function generateForProduct(Product $product): Product
    {
        $catName = $product->category ? $product->category->name : 'Gadgets';
        $priceFormatted = BanglaHelper::formatTaka($product->effective_price);

        // 1. Meta Title (Max 60 chars optimal)
        $metaTitle = "{$product->name} - Buy Online in BD | Best Price {$priceFormatted}";
        if (strlen($metaTitle) > 65) {
            $metaTitle = Str::limit("{$product->name} | Best Price {$priceFormatted} in Bangladesh", 60);
        }

        // 2. Meta Description (150-160 chars optimal)
        $bnSubtitle = $product->name_bn ? " ({$product->name_bn})" : "";
        $metaDescription = "Buy original {$product->name}{$bnSubtitle} at lowest price in Bangladesh. Official warranty, 24h fast delivery in Dhaka & 64 districts. Cash on delivery & bKash available at NEXUS DOKAN!";
        $metaDescription = Str::limit($metaDescription, 160);

        // 3. Meta Keywords
        $keywords = [
            $product->name,
            $product->name_bn ?: '',
            $catName,
            "buy {$product->name} in Bangladesh",
            "{$product->name} price in BD",
            "online gadget shop BD",
            "cash on delivery gadgets Dhaka",
        ];
        $metaKeywords = implode(', ', array_filter($keywords));

        // 4. Calculate SEO Health Score
        $score = 85;
        if (!empty($product->thumbnail)) $score += 5;
        if (!empty($product->description)) $score += 5;
        if ($product->images && count($product->images) > 1) $score += 5;

        $product->update([
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'meta_keywords' => $metaKeywords,
            'seo_score' => min(100, $score),
        ]);

        return $product;
    }

    /**
     * Generate Google JSON-LD Rich Snippet Schema
     */
    public static function generateJsonLdSchema(Product $product): array
    {
        return [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $product->thumbnail ? url($product->thumbnail) : url('/images/logo.png'),
            'description' => $product->meta_description ?: $product->short_description,
            'sku' => $product->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand ? $product->brand->name : 'NEXUS DOKAN',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.show', $product->slug),
                'priceCurrency' => 'BDT',
                'price' => (float) $product->effective_price,
                'priceValidUntil' => now()->addYear()->format('Y-m-d'),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'NEXUS DOKAN BD',
                ],
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => (float) ($product->rating ?: 5.0),
                'reviewCount' => (int) ($product->reviews_count ?: 1),
            ],
        ];
    }

    /**
     * Build Dynamic XML Sitemap
     */
    public static function buildSitemapXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Home
        $xml .= "  <url>\n    <loc>" . url('/') . "</loc>\n    <priority>1.0</priority>\n    <changefreq>daily</changefreq>\n  </url>\n";
        // Shop Catalog
        $xml .= "  <url>\n    <loc>" . url('/shop') . "</loc>\n    <priority>0.9</priority>\n    <changefreq>daily</changefreq>\n  </url>\n";

        // Products
        $products = Product::where('status', 'active')->get();
        foreach ($products as $p) {
            $xml .= "  <url>\n    <loc>" . route('product.show', $p->slug) . "</loc>\n    <priority>0.8</priority>\n    <changefreq>weekly</changefreq>\n  </url>\n";
        }

        // Categories
        $categories = Category::where('status', 'active')->get();
        foreach ($categories as $c) {
            $xml .= "  <url>\n    <loc>" . url('/shop?category=' . $c->slug) . "</loc>\n    <priority>0.7</priority>\n    <changefreq>weekly</changefreq>\n  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Batch generate SEO tags for all active products
     */
    public static function generateForAllProducts(): int
    {
        $products = Product::all();
        $count = 0;
        foreach ($products as $product) {
            self::generateForProduct($product);
            $count++;
        }
        return $count;
    }
}
