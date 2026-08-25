<?php

namespace App\Services;

use App\Models\Product;
use App\Helpers\BanglaHelper;

class AiMarketingCopyService
{
    /**
     * Generate high-converting Facebook & Instagram Ad copy for a product
     */
    public static function generateAdCopy(Product $product, string $tone = 'sales_boost'): array
    {
        $priceFormatted = BanglaHelper::formatTaka($product->final_price);
        $regularFormatted = BanglaHelper::formatTaka($product->price);
        $discountPercent = $product->discount_percent;

        // 1. Facebook High-Converting Ad Post
        $fbHook = "🔥 সীমিত সময়ের জন্য স্পেশাল ধামাকা অফার! আপনার কাঙ্ক্ষিত {$product->name} এখন সেরা মূল্যে!";
        $fbBullets = "✨ প্রিমিয়াম কোয়ালিটি ও আধুনিক ডিজাইন\n🚀 সারা বাংলাদেশে দ্রুত হোম ডেলিভারি\n💵 ক্যাশ অন ডেলিভারি (পণ্য দেখে মূল্য পরিশোধের সুবিধা)\n🛡️ ৭ দিনের ইজি রিপ্লেসমেন্ট গ্যারান্টি";
        
        if ($discountPercent > 0) {
            $fbPricing = "💰 অফার মূল্য: মাত্র {$priceFormatted} (আগের দাম: {$regularFormatted} - {$discountPercent}% ছাড়!)";
        } else {
            $fbPricing = "💰 অফার মূল্য: মাত্র {$priceFormatted} টাকা!";
        }

        $fbCta = "👉 এখনই অর্ডার করতে 'Send Message' বাটনে ক্লিক করুন অথবা ভিজিট করুন:\n🔗 " . url("/product/{$product->slug}");

        $fbCopy = "{$fbHook}\n\n{$fbBullets}\n\n{$fbPricing}\n\n{$fbCta}\n\n📞 হটলাইন: +8809678831374";

        // 2. Instagram Trendy Caption & Hashtags
        $productKeyword = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $product->name));
        $igHashtags = "#{$productKeyword} #NexusDokan #BDShopping #OnlineShoppingBD #DhakaShopping #BanglaGadgets #TechBD #TrendingBD";
        
        $igCaption = "Upgrade your lifestyle with {$product->name}! ⚡ Premium quality at an unbeatable price of {$priceFormatted}.\n\n📦 Cash on Delivery All Over Bangladesh.\n🛍️ Tap link in bio to shop now!\n\n{$igHashtags}";

        // 3. SMS / WhatsApp Marketing Broadcast Copy (under 160 chars)
        $smsCopy = "স্পেশাল অফার! {$product->name} এখন মাত্র {$priceFormatted} টাকায়! আজই অর্ডার করুন: " . url("/product/{$product->slug}") . " হটলাইন: 09678831374";

        return [
            'facebook_ad_copy' => $fbCopy,
            'instagram_caption' => $igCaption,
            'sms_marketing_copy' => $smsCopy,
            'product_name' => $product->name,
            'price_formatted' => $priceFormatted,
        ];
    }
}
