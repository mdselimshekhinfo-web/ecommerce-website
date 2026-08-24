<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Helpers\BanglaHelper;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function ask(Request $request)
    {
        $message = strtolower(trim($request->input('message', '')));

        if (empty($message)) {
            return response()->json([
                'reply' => "👋 Hi there! I'm Aura, your AI Cyber Shopping Assistant. How can I help you today? You can ask about delivery in BD, bKash payment, best gadgets, or current coupons!",
            ]);
        }

        // Logic routing for smart assistant responses
        if (str_contains($message, 'delivery') || str_contains($message, 'ডেলিভারি') || str_contains($message, 'shipping') || str_contains($message, 'charge')) {
            $reply = "📦 **Bangladesh Delivery Policy:**\n\n• **Inside Dhaka:** ৳60 (Delivered within 24 hours via Express Courier)\n• **Outside Dhaka (All 64 Districts):** ৳120 (Delivered in 48-72 hours via Steadfast / Pathao)\n• **Free Delivery:** Use coupon `FREESHIPBD` on orders above ৳2,000!";
        } elseif (str_contains($message, 'bkash') || str_contains($message, 'বিকাশ') || str_contains($message, 'nagad') || str_contains($message, 'নগদ') || str_contains($message, 'payment') || str_contains($message, 'পেমেন্ট') || str_contains($message, 'cod')) {
            $reply = "💳 **Payment Methods Available:**\n\n1. **bKash Direct / Seamless Gateway** (Instant auto-verification)\n2. **Nagad & Rocket**\n3. **Cash on Delivery (COD)** anywhere in Bangladesh\n4. **Visa / Mastercard / AMEX** cards";
        } elseif (str_contains($message, 'coupon') || str_contains($message, 'কুপন') || str_contains($message, 'discount') || str_contains($message, 'offer') || str_contains($message, 'ছাড়')) {
            $reply = "🎁 **Active Discount Vouchers:**\n\n• `CYBER10` - 10% Off on orders over ৳1,000\n• `NEXUS200` - Flat ৳200 Off on orders over ৳1,500\n• `FREESHIPBD` - Free Shipping across BD\n• 🎡 Or try spinning our **Lucky Cyber Wheel** on the home page!";
        } elseif (str_contains($message, 'earbud') || str_contains($message, 'headphone') || str_contains($message, 'audio') || str_contains($message, 'হেডফোন') || str_contains($message, 'ইয়ারবাড')) {
            $products = Product::where('category_id', 1)->take(2)->get();
            $p1 = $products[0] ?? null;
            $reply = "🎧 Check out our top-selling **AuraBlade ANC Cyber Earbuds Pro** for " . BanglaHelper::formatTaka($p1 ? $p1->effective_price : 2950) . " with -48dB Active Noise Cancellation and 45ms gaming mode!";
        } elseif (str_contains($message, 'watch') || str_contains($message, 'ঘড়ি') || str_contains($message, 'smartwatch')) {
            $reply = "⌚ I highly recommend the **Chronos-X Holographic AMOLED Smartwatch** (৳4,490). It features a 2.04\" 120Hz curved AMOLED screen, Titanium chassis, Bangla notifications, and 14-day battery!";
        } elseif (str_contains($message, 'keyboard') || str_contains($message, 'কিবোর্ড') || str_contains($message, 'mouse') || str_contains($message, 'মাউস') || str_contains($message, 'gaming')) {
            $reply = "⚡ Check out our **Vortex 75% Mechanical Keyboard** (creamy thock switches) or the **Phantom-X 8K Wireless Gaming Mouse** (49g ultra-lightweight) in the Quantum Peripherals section!";
        } elseif (str_contains($message, 'contact') || str_contains($message, 'help') || str_contains($message, 'support') || str_contains($message, 'ঠিকানা') || str_contains($message, 'ফোন')) {
            $reply = "📞 **NEXUS DOKAN HQ Support:**\n\n• Hotline: +880 1711-000111 (9 AM - 11 PM)\n• Email: support@nexusdokan.bd\n• Experience Center: Gulshan-2, Dhaka, Bangladesh";
        } else {
            $reply = "🤖 I found several cyber gadgets matching your query! Feel free to browse our **Shop** or search directly in the top search bar. Would you like product recommendations under ৳3,000 or ৳5,000?";
        }

        return response()->json([
            'reply' => $reply,
        ]);
    }
}
