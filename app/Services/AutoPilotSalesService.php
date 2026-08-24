<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\ThemeSetting;
use App\Helpers\BanglaHelper;
use App\Helpers\LocalizationHelper;
use Illuminate\Support\Str;

class AutoPilotSalesService
{
    /**
     * Process an incoming customer message and return AI/Agent response
     */
    public static function processCustomerMessage(ChatSession $session, string $rawMessage): array
    {
        $message = trim($rawMessage);
        $lower = strtolower($message);
        $isBn = LocalizationHelper::getLocale() === 'bn';

        // Check if Human Agent is requested
        if (str_contains($lower, 'agent') || str_contains($lower, 'human') || str_contains($lower, 'প্রতিনিধি') || str_contains($lower, 'কথা বলতে চাই') || str_contains($lower, 'মানুষ')) {
            $agentStatus = ThemeSetting::get('agent_online_status', 'offline');
            $autoPilot = ThemeSetting::get('ai_autopilot_mode', '1');

            if ($agentStatus === 'online') {
                $session->update([
                    'is_assigned_to_human' => true,
                    'status' => 'active',
                ]);

                $replyText = $isBn 
                    ? "👨‍💼 একজন সাপোর্ট প্রতিনিধি আপনার সাথে যুক্ত হচ্ছেন। অনুগ্রহ করে অপেক্ষা করুন..." 
                    : "👨‍💼 Connecting you to a live support agent. Please hold on...";

                return [
                    'reply' => $replyText,
                    'type' => 'text',
                    'payload' => null,
                ];
            } else {
                // Agent is offline - Auto Pilot steps in or offers WhatsApp
                $storePhone = ThemeSetting::get('store_phone', '01711000111');
                $waPhone = preg_replace('/[^0-9]/', '', $storePhone);
                if (strlen($waPhone) === 11 && str_starts_with($waPhone, '01')) {
                    $waPhone = '88' . $waPhone;
                }

                $replyText = $isBn
                    ? "🤖 আমাদের সাপোর্ট প্রতিনিধি বর্তমানে অফলাইনে আছেন। চিন্তার কোনো কারণ নেই — আমি (AI সেলস এজেন্ট) আপনার অর্ডারটি সরাসরি কনফার্ম করে দিতে প্রস্তুত!\n\nশুধু আপনার পছন্দের **প্রোডাক্টের নাম, মোবাইল নম্বর ও ডেলিভারি ঠিকানা** লিখুন। অথবা সরাসরি হোয়াটসঅ্যাপে মেসেজ দিন:"
                    : "🤖 Our human representatives are currently away. No worries — I am your AI Auto-Pilot Sales Agent ready to book your order directly!\n\nPlease provide your **Product Name, Phone Number & Full Address** below. Or chat directly on WhatsApp:";

                return [
                    'reply' => $replyText,
                    'type' => 'whatsapp_redirect',
                    'payload' => [
                        'whatsapp_url' => "https://wa.me/{$waPhone}?text=" . rawurlencode("Hello NEXUS DOKAN! I need assistance with an order."),
                        'button_text' => $isBn ? '💬 WhatsApp এ মেসেজ দিন' : '💬 Chat on WhatsApp',
                    ],
                ];
            }
        }

        // Try extracting Phone & Address for Direct AI Order Booking
        $extractedPhone = self::extractPhoneNumber($message);
        $extractedAddress = self::extractAddress($message);

        // Check if message contains purchasing intention or has Cart / Product + Phone + Address
        if ($extractedPhone && $extractedAddress) {
            return self::createAutoPilotOrder($session, $message, $extractedPhone, $extractedAddress, $isBn);
        }

        // Check Order Tracking Query (#1024 or 017XXXXXXXX)
        if (preg_match('/#?([A-Za-z0-9\-]{4,12})/', $message, $m) && (str_contains($lower, 'track') || str_contains($lower, 'ট্র্যাক') || str_contains($lower, 'অর্ডার') || str_contains($lower, 'status'))) {
            $searchTerm = trim($m[1], '#');
            $order = Order::where('order_number', $searchTerm)
                ->orWhere('customer_phone', 'like', "%{$searchTerm}%")
                ->latest()
                ->first();

            if ($order) {
                $statusBn = [
                    'pending' => 'পেন্ডিং (প্যাকিংয়ের অপেক্ষায়)',
                    'processing' => 'প্রসেসিং সম্পন্ন',
                    'shipped' => 'কুরিয়ারে ট্রানজিটে আছে',
                    'delivered' => 'ডেলিভারি সম্পন্ন',
                    'cancelled' => 'বাতিল',
                ][$order->order_status] ?? $order->order_status;

                $reply = $isBn 
                    ? "📦 **অর্ডার ট্র্যাকিং তথ্য (#{$order->order_number}):**\n\n• **গ্রাহক:** {$order->customer_name}\n• **মোট বিল:** " . BanglaHelper::formatTaka($order->total_amount) . "\n• **বর্তমান স্ট্যাটাস:** {$statusBn}\n• **ডেলিভারি ঠিকানা:** {$order->delivery_address}\n\nআপনার পার্সেলটি যথাসময়ে আপনার ঠিকানায় পৌঁছে যাবে ইনশাআল্লাহ!"
                    : "📦 **Order Tracking Details (#{$order->order_number}):**\n\n• **Customer:** {$order->customer_name}\n• **Total:** " . BanglaHelper::formatTaka($order->total_amount) . "\n• **Current Status:** " . strtoupper($order->order_status) . "\n• **Address:** {$order->delivery_address}\n\nYour parcel is on its way!";

                return ['reply' => $reply, 'type' => 'text', 'payload' => null];
            }
        }

        // Standard Intelligent Knowledge Base
        return self::generateSmartKnowledgeReply($lower, $isBn);
    }

    /**
     * Create an automatic order via AI Auto-Pilot
     */
    private static function createAutoPilotOrder(ChatSession $session, string $message, string $phone, string $address, bool $isBn): array
    {
        // Find targeted product (from message keywords or cart or default top product)
        $product = null;
        $allProducts = Product::where('status', 'active')->where('stock_quantity', '>', 0)->get();
        
        foreach ($allProducts as $p) {
            $pNameLower = strtolower($p->name);
            if (str_contains(strtolower($message), $pNameLower) || str_contains(strtolower($message), strtolower($p->slug))) {
                $product = $p;
                break;
            }
        }

        if (!$product) {
            // Check session cart
            if (!empty($session->cart_summary) && is_array($session->cart_summary)) {
                $firstCartItem = reset($session->cart_summary);
                $product = Product::find($firstCartItem['product_id'] ?? null);
            }
        }

        // Fallback to top featured product if still null
        if (!$product) {
            $product = Product::where('status', 'active')->orderBy('sales_count', 'desc')->first();
        }

        if (!$product) {
            return [
                'reply' => $isBn ? "দুঃখিত, কোনো সক্রিয় প্রোডাক্ট পাওয়া যায়নি।" : "Sorry, no active products found.",
                'type' => 'text',
                'payload' => null,
            ];
        }

        // Determine District & Delivery Fee
        $isDhaka = str_contains(strtolower($address), 'dhaka') || str_contains(strtolower($address), 'ঢাকা') || str_contains(strtolower($address), 'mirpur') || str_contains(strtolower($address), 'uttara') || str_contains(strtolower($address), 'dhanmondi') || str_contains(strtolower($address), 'gulshan');
        $district = $isDhaka ? 'Dhaka' : 'Outside Dhaka';
        $shippingCost = $isDhaka ? 60 : 120;
        $subtotal = (float) $product->effective_price;
        $totalAmount = $subtotal + $shippingCost;

        // Customer Name detection
        $customerName = $session->customer_name ?: 'Valued Customer';
        if (preg_match('/নাম[:\s]+([^\n,]+)/iu', $message, $nm)) {
            $customerName = trim($nm[1]);
        } elseif (preg_match('/name[:\s]+([^\n,]+)/i', $message, $nm)) {
            $customerName = trim($nm[1]);
        }

        // Generate Order
        $orderNumber = 'AI-' . strtoupper(Str::random(6));
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $session->user_id,
            'customer_name' => $customerName,
            'customer_email' => null,
            'customer_phone' => $phone,
            'delivery_district' => $district,
            'delivery_address' => $address,
            'delivery_notes' => 'Booked automatically via AI Auto-Pilot Sales Agent',
            'shipping_zone' => $isDhaka ? 'inside_dhaka' : 'outside_dhaka',
            'shipping_cost' => $shippingCost,
            'subtotal' => $subtotal,
            'discount_amount' => 0,
            'total_amount' => $totalAmount,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'admin_notes' => '🤖 Booked via AI Auto-Pilot Sales Agent',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'variant' => null,
            'price' => $product->effective_price,
            'quantity' => 1,
            'total' => $product->effective_price,
        ]);

        // Reduce inventory
        $product->decrement('stock_quantity', 1);
        $product->increment('sales_count', 1);

        // Update Session info
        $session->update([
            'customer_name' => $customerName,
            'customer_phone' => $phone,
        ]);

        $replyText = $isBn 
            ? "🎉 **অভিনন্দন! আপনার অর্ডারটি সফলভাবে কনফার্ম করা হয়েছে!**\n\n• **অর্ডার নম্বর:** #{$orderNumber}\n• **পণ্য:** {$product->name}\n• **মূল্য:** " . BanglaHelper::formatTaka($subtotal) . "\n• **ডেলিভারি চার্জ:** " . BanglaHelper::formatTaka($shippingCost) . "\n• **সর্বমোট প্রদেয় বিল (ক্যাশ অন ডেলিভারি):** " . BanglaHelper::formatTaka($totalAmount) . "\n• **ডেলিভারি ঠিকানা:** {$address}\n• **মোবাইল:** {$phone}\n\nআমাদের ডেলিভারি টিম দ্রুততম সময়ে আপনার পার্সেলটি পৌঁছে দেবে। ধন্যবাদ!"
            : "🎉 **Congratulations! Your order has been placed successfully via AI Auto-Pilot!**\n\n• **Order Number:** #{$orderNumber}\n• **Product:** {$product->name}\n• **Product Price:** " . BanglaHelper::formatTaka($subtotal) . "\n• **Delivery Charge:** " . BanglaHelper::formatTaka($shippingCost) . "\n• **Total Bill (Cash on Delivery):** " . BanglaHelper::formatTaka($totalAmount) . "\n• **Address:** {$address}\n• **Phone:** {$phone}\n\nOur team is preparing your package for express dispatch. Thank you!";

        return [
            'reply' => $replyText,
            'type' => 'order_receipt',
            'payload' => [
                'order_id' => $order->id,
                'order_number' => $orderNumber,
                'product_name' => $product->name,
                'total_amount' => $totalAmount,
                'delivery_address' => $address,
            ],
        ];
    }

    /**
     * Extract 11 digit BD phone number
     */
    private static function extractPhoneNumber(string $text): ?string
    {
        if (preg_match('/(01[3-9]\d{8})/', $text, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Extract delivery address
     */
    private static function extractAddress(string $text): ?string
    {
        $patterns = [
            '/ঠিকানা[:\s]+([^\n,]+(?:,[^\n,]+)*)/iu',
            '/address[:\s]+([^\n,]+(?:,[^\n,]+)*)/i',
            '/বাসা[:\s]+([^\n,]+)/iu',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                return trim($m[1]);
            }
        }

        // Check if text has location words
        $locationWords = ['dhaka', 'ঢাকা', 'chittagong', 'চট্টগ্রাম', 'sylhet', 'সিলেট', 'mirpur', 'মিরপুর', 'uttara', 'উত্তরা', 'dhanmondi', 'ধানমন্ডি', 'gulshan', 'গুলশান', 'gazipur', 'গাজীপুর'];
        foreach ($locationWords as $lw) {
            if (str_contains(strtolower($text), $lw)) {
                return trim($text);
            }
        }

        return null;
    }

    /**
     * Generate smart answers
     */
    private static function generateSmartKnowledgeReply(string $lower, bool $isBn): array
    {
        if (str_contains($lower, 'delivery') || str_contains($lower, 'ডেলিভারি') || str_contains($lower, 'shipping') || str_contains($lower, 'charge')) {
            $reply = $isBn 
                ? "📦 **ডেলিভারি চার্জ ও সময়সূচী:**\n\n• **ঢাকার ভিতরে:** ৳৬০ (২৪ ঘণ্টার মধ্যে দ্রুততম এক্সপ্রেস ডেলিভারি)\n• **ঢাকার বাইরে (৬৪ জেলায়):** ৳১২০ (৪৮-৭২ ঘণ্টার মধ্যে Steadfast / Pathao হোম ডেলিভারি)\n• **ফ্রি ডেলিভারি:** ৳২,০০০ এর বেশি অর্ডারে `FREESHIPBD` কুপন কোড দিন!"
                : "📦 **Bangladesh Delivery Policy:**\n\n• **Inside Dhaka:** ৳60 (24h Express Delivery)\n• **Outside Dhaka (All 64 Districts):** ৳120 (48-72h Doorstep Delivery via Steadfast / Pathao)\n• **Free Delivery:** Use coupon `FREESHIPBD` on orders over ৳2,000!";
        } elseif (str_contains($lower, 'bkash') || str_contains($lower, 'বিকাশ') || str_contains($lower, 'nagad') || str_contains($lower, 'নগদ') || str_contains($lower, 'payment') || str_contains($lower, 'পেমেন্ট') || str_contains($lower, 'cod')) {
            $reply = $isBn
                ? "💳 **পেমেন্ট পদ্ধতিসমূহ:**\n\n১. **ক্যাশ অন ডেলিভারি (COD):** পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন\n২. **বিকাশ সরাসরি পেমেন্ট (bKash Direct)**\n৩. **নগদ ও রকেট ডিজিটাল পেমেন্ট**\n৪. **ভিসা / মাস্টারকার্ড**"
                : "💳 **Payment Methods Available:**\n\n1. **Cash on Delivery (COD)** nationwide\n2. **bKash Direct / Seamless Gateway**\n3. **Nagad & Rocket**\n4. **Visa / Mastercard**";
        } elseif (str_contains($lower, 'coupon') || str_contains($lower, 'কুপন') || str_contains($lower, 'discount') || str_contains($lower, 'offer') || str_contains($lower, 'ছাড়')) {
            $reply = $isBn
                ? "🎁 **বর্তমান সক্রিয় ডিসকাউন্ট কুপন:**\n\n• `CYBER10` - ১০% ডিসকাউন্ট (১০০০ টাকার বেশি অর্ডারে)\n• `NEXUS200` - ফ্ল্যাট ৳২০০ ক্যাশ ছাড়\n• `FREESHIPBD` - ফ্রি হোম ডেলিভারি\n• 🎡 অথবা আমাদের হোমপেজে থাকা **লাকি স্পিন হুইল** ঘুরে জিতে নিন ৳৫০০ ভাউচার!"
                : "🎁 **Active Discount Vouchers:**\n\n• `CYBER10` - 10% Off on orders over ৳1,000\n• `NEXUS200` - Flat ৳200 Off\n• `FREESHIPBD` - Free Shipping across BD\n• 🎡 Or spin our **Lucky Cyber Wheel** for ৳500 mega voucher!";
        } else {
            $reply = $isBn
                ? "🤖 আমি আপনাকে সাহায্য করতে প্রস্তুত! আপনি যেকোনো গ্যাজেটের দাম, ডেলিভারি চার্জ, কুপন বা অর্ডার ট্র্যাকিং জানতে পারেন। সরাসরি অর্ডার করতে আপনার **মোবাইল নম্বর ও ঠিকানা** লিখে পাঠিয়ে দিন!"
                : "🤖 I'm here to assist! Ask me about gadget specs, delivery rates, coupons, or order tracking. To order directly, just type your **phone number & address**!";
        }

        return ['reply' => $reply, 'type' => 'text', 'payload' => null];
    }
}
