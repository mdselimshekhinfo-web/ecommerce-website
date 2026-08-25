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

        // 1. Check if Human Agent is requested
        if (str_contains($lower, 'agent') || str_contains($lower, 'human') || str_contains($lower, 'প্রতিনিধি') || str_contains($lower, 'কথা বলতে চাই') || str_contains($lower, 'মানুষ') || str_contains($lower, 'সাপোর্ট')) {
            $agentStatus = ThemeSetting::get('agent_online_status', 'offline');

            if ($agentStatus === 'online') {
                $session->update([
                    'is_assigned_to_human' => true,
                    'status' => 'active',
                ]);

                $replyText = $isBn 
                    ? "👨‍💼 একজন সাপোর্ট প্রতিনিধি আপনার সাথে যুক্ত হচ্ছেন। অনুগ্রহ করে কয়েক সেকেন্ড অপেক্ষা করুন..." 
                    : "👨‍💼 Connecting you to a live human representative. Please hold on...";

                return [
                    'reply' => $replyText,
                    'type' => 'text',
                    'payload' => null,
                    'chips' => $isBn ? ['📦 অর্ডার ট্র্যাক', '💳 পেমেন্ট পদ্ধতি', '🎁 কুপন ও অফার'] : ['Track Order', 'Payment Methods', 'Coupons'],
                ];
            } else {
                $storePhone = ThemeSetting::get('store_phone', '01711000111');
                $waPhone = preg_replace('/[^0-9]/', '', $storePhone);
                if (strlen($waPhone) === 11 && str_starts_with($waPhone, '01')) {
                    $waPhone = '88' . $waPhone;
                }

                $replyText = $isBn
                    ? "🤖 আমাদের কাস্টমার সাপোর্ট টিম বর্তমানে অফলাইনে আছেন। কোনো সমস্যা নেই—আমি আপনার এআই সেলস অ্যাসিস্ট্যান্ট!\n\nআপনি চাইলে সরাসরি এখানে আপনার **পছন্দের পণ্য, ফোন নম্বর ও ঠিকানা** লিখে ১-ক্লিকে অর্ডার কনফার্ম করতে পারেন, অথবা সরাসরি আমাদের WhatsApp-এ কথা বলতে পারেন:"
                    : "🤖 Our human representatives are currently away. No worries — I am your AI Auto-Pilot Sales Agent ready to book your order directly!\n\nPlease write your **Product Name, Phone Number & Full Address** below, or connect via WhatsApp:";

                return [
                    'reply' => $replyText,
                    'type' => 'whatsapp_redirect',
                    'payload' => [
                        'whatsapp_url' => "https://wa.me/{$waPhone}?text=" . rawurlencode("Hello NEXUS DOKAN! I need assistance with an order."),
                        'button_text' => $isBn ? '💬 WhatsApp এ মেসেজ দিন' : '💬 Chat on WhatsApp',
                    ],
                    'chips' => $isBn ? ['🔥 সেরা ডিলস', '🎧 ইয়ারবাডস', '⌨️ কিবোর্ড', '📦 ডেলিভারি চার্জ'] : ['Best Deals', 'Earbuds', 'Keyboards', 'Delivery'],
                ];
            }
        }

        // 2. Try extracting Phone & Address for Direct AI Order Booking
        $extractedPhone = self::extractPhoneNumber($message);
        $extractedAddress = self::extractAddress($message);

        if ($extractedPhone && $extractedAddress) {
            return self::createAutoPilotOrder($session, $message, $extractedPhone, $extractedAddress, $isBn);
        }

        // 3. Check Order Tracking Query (#1024 or 017XXXXXXXX or 'track')
        if (preg_match('/#?([A-Za-z0-9\-]{4,15})/', $message, $m) && (str_contains($lower, 'track') || str_contains($lower, 'ট্র্যাক') || str_contains($lower, 'অর্ডার') || str_contains($lower, 'status') || str_contains($lower, 'কোথায়'))) {
            $searchTerm = trim($m[1], '#');
            $order = Order::where('order_number', $searchTerm)
                ->orWhere('customer_phone', 'like', "%{$searchTerm}%")
                ->orWhere('tracking_code', $searchTerm)
                ->latest()
                ->first();

            if ($order) {
                $statuses = [
                    'pending' => ['label' => $isBn ? 'পেন্ডিং' : 'Pending', 'step' => 1],
                    'processing' => ['label' => $isBn ? 'প্রসেসিং' : 'Processing', 'step' => 2],
                    'shipped' => ['label' => $isBn ? 'কুরিয়ারে ট্রানজিট' : 'In Transit', 'step' => 3],
                    'delivered' => ['label' => $isBn ? 'ডেলিভারি সম্পন্ন' : 'Delivered', 'step' => 4],
                    'cancelled' => ['label' => $isBn ? 'বাতিল' : 'Cancelled', 'step' => 0],
                ];
                $cur = $statuses[$order->order_status] ?? ['label' => $order->order_status, 'step' => 1];

                $reply = $isBn 
                    ? "📦 **অর্ডার ট্র্যাকিং আপডেট (#{order_num}):**\n• **গ্রাহক:** {$order->customer_name}\n• **মোট বিল:** " . BanglaHelper::formatTaka($order->total_amount) . "\n• **বর্তমান স্ট্যাটাস:** {$cur['label']}\n• **কুরিয়ার ট্র্যাকিং:** " . ($order->tracking_code ?: 'TRK-PENDING') . "\n• **ঠিকানা:** {$order->delivery_address}"
                    : "📦 **Order Tracking (#{$order->order_number}):**\n• **Customer:** {$order->customer_name}\n• **Total:** " . BanglaHelper::formatTaka($order->total_amount) . "\n• **Status:** {$cur['label']}\n• **Courier Tracking:** " . ($order->tracking_code ?: 'TRK-PENDING') . "\n• **Address:** {$order->delivery_address}";

                return [
                    'reply' => str_replace('{order_num}', $order->order_number, $reply),
                    'type' => 'order_tracking',
                    'payload' => [
                        'order_number' => $order->order_number,
                        'customer_name' => $order->customer_name,
                        'total_amount' => $order->total_amount,
                        'order_status' => $order->order_status,
                        'status_label' => $cur['label'],
                        'step' => $cur['step'],
                        'tracking_code' => $order->tracking_code ?: 'TRK-PENDING',
                        'courier_name' => $order->courier_name ?: 'Steadfast Courier',
                    ],
                    'chips' => $isBn ? ['🔥 নতুন অফার দেখুন', '💬 প্রতিনিধির সাথে কথা বলুন'] : ['View Deals', 'Talk to Agent'],
                ];
            }
        }

        // 4. Check for Product Search & Category Inquiries (e.g. Earbuds, Keyboards, Watches, Price range)
        $productMatches = self::searchMatchingProducts($lower);
        if ($productMatches->isNotEmpty()) {
            $count = $productMatches->count();
            $reply = $isBn
                ? "🎯 আপনার জন্য সেরা **{$count}টি গ্যাজেট** খুঁজে পেয়েছি! সরাসরি নিচে থেকে পছন্দ করে কার্টে যোগ বা অর্ডার করতে পারেন:"
                : "🎯 Found **{$count} matching gadgets** for you! You can order directly below:";

            $cards = $productMatches->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'thumbnail' => $p->thumbnail,
                    'price' => BanglaHelper::formatTaka($p->effective_price),
                    'raw_price' => $p->effective_price,
                    'original_price' => $p->sale_price ? BanglaHelper::formatTaka($p->price) : null,
                    'rating' => $p->rating ?: 4.9,
                    'in_stock' => $p->stock_quantity > 0,
                    'badge' => $p->badge ?: ($p->discount_percent > 0 ? "-{$p->discount_percent}%" : null),
                ];
            })->values()->toArray();

            return [
                'reply' => $reply,
                'type' => 'product_carousel',
                'payload' => [
                    'products' => $cards,
                ],
                'chips' => $isBn ? ['📦 ডেলিভারি চার্জ কত?', '🎁 ডিসকাউন্ট কুপন', '💳 বিকাশ পেমেন্ট'] : ['Delivery Charges', 'Discount Coupon', 'Payment Policy'],
            ];
        }

        // 5. Check for Coupon & Discount Inquiries
        if (str_contains($lower, 'coupon') || str_contains($lower, 'কুপন') || str_contains($lower, 'discount') || str_contains($lower, 'offer') || str_contains($lower, 'ছাড়') || str_contains($lower, 'ভাউচার') || str_contains($lower, 'কম')) {
            $reply = $isBn
                ? "🎁 **আজকের সেরা ডিসকাউন্ট কুপনসমূহ:**\nনিচের কুপন কোডগুলো চেকআউটে ব্যবহার করে অতিরিক্ত ছাড় উপভোগ করুন:"
                : "🎁 **Active Discount Vouchers:**\nApply these exclusive codes during checkout:";

            $coupons = [
                ['code' => 'CYBER10', 'desc' => $isBn ? '১০% অতিরিক্ত ছাড় (৳১,০০০+ অর্ডারে)' : '10% Off on orders ৳1,000+'],
                ['code' => 'NEXUS200', 'desc' => $isBn ? '৳২০০ ফ্ল্যাট ছাড়' : 'Flat ৳200 Off on orders'],
                ['code' => 'FREESHIPBD', 'desc' => $isBn ? 'ফ্রি হোম ডেলিভারি (৳২,০০০+ অর্ডারে)' : 'Free Shipping across BD'],
            ];

            return [
                'reply' => $reply,
                'type' => 'coupon_cards',
                'payload' => [
                    'coupons' => $coupons,
                ],
                'chips' => $isBn ? ['🎧 ANC ইয়ারবাডস', '⌨️ মেকানিক্যাল কিবোর্ড', '⌚ স্মার্টওয়াচ'] : ['Earbuds', 'Keyboards', 'Smartwatches'],
            ];
        }

        // 6. Standard Intelligent Knowledge Base
        return self::generateSmartKnowledgeReply($lower, $isBn);
    }

    /**
     * Search products based on query keywords
     */
    private static function searchMatchingProducts(string $lower)
    {
        $keywords = [
            'earbud' => 'audio',
            'headphone' => 'audio',
            'হেডফোন' => 'audio',
            'ইয়ারবাড' => 'audio',
            'anc' => 'audio',
            'sound' => 'audio',
            'গান' => 'audio',
            'keyboard' => 'keyboard',
            'কিবোর্ড' => 'keyboard',
            'mechanical' => 'keyboard',
            'mouse' => 'mouse',
            'মাউস' => 'mouse',
            'watch' => 'watch',
            'smartwatch' => 'watch',
            'ঘড়ি' => 'watch',
            'স্মার্টওয়াচ' => 'watch',
            'powerbank' => 'power',
            'চার্জার' => 'power',
            'power' => 'power',
            'bag' => 'bag',
            'ব্যাগ' => 'bag',
            'best' => 'featured',
            'সেরা' => 'featured',
            'offer' => 'featured',
            'পণ্য' => 'featured',
            'gadget' => 'featured',
            'গ্যাজেট' => 'featured',
        ];

        $matchedType = null;
        foreach ($keywords as $word => $type) {
            if (str_contains($lower, $word)) {
                $matchedType = $type;
                break;
            }
        }

        if ($matchedType) {
            if ($matchedType === 'featured') {
                return Product::where('status', 'active')
                    ->where('stock_quantity', '>', 0)
                    ->orderBy('sales_count', 'desc')
                    ->take(3)
                    ->get();
            }

            return Product::where('status', 'active')
                ->where(function ($q) use ($lower) {
                    $q->where('name', 'like', "%{$lower}%")
                      ->orWhere('description', 'like', "%{$lower}%")
                      ->orWhereHas('category', function ($cq) use ($lower) {
                          $cq->where('name', 'like', "%{$lower}%")
                            ->orWhere('slug', 'like', "%{$lower}%");
                      });
                })
                ->where('stock_quantity', '>', 0)
                ->take(3)
                ->get();
        }

        // Also check if any product name is explicitly mentioned
        $allProducts = Product::where('status', 'active')->where('stock_quantity', '>', 0)->get();
        foreach ($allProducts as $p) {
            if (str_contains($lower, strtolower($p->name)) || str_contains($lower, strtolower($p->slug))) {
                return collect([$p]);
            }
        }

        return collect();
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
            if (!empty($session->cart_summary) && is_array($session->cart_summary)) {
                $firstCartItem = reset($session->cart_summary);
                $product = Product::find($firstCartItem['product_id'] ?? null);
            }
        }

        if (!$product) {
            $product = Product::where('status', 'active')->orderBy('sales_count', 'desc')->first();
        }

        if (!$product) {
            return [
                'reply' => $isBn ? "দুঃখিত, কোনো সক্রিয় প্রোডাক্ট পাওয়া যায়নি।" : "Sorry, no active products found.",
                'type' => 'text',
                'payload' => null,
                'chips' => $isBn ? ['🔥 সেরা অফার', '📦 ডেলিভারি চার্জ'] : ['Best Deals', 'Delivery Charge'],
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
        $orderNumber = 'NX-' . strtoupper(Str::random(6));
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
            'verification_status' => 'unverified',
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

        // Inventory update
        $product->decrement('stock_quantity', 1);
        $product->increment('sales_count', 1);

        // Update Session info
        $session->update([
            'customer_name' => $customerName,
            'customer_phone' => $phone,
        ]);

        $replyText = $isBn 
            ? "🎉 **অভিনন্দন {$customerName}! আপনার অর্ডারটি সফলভাবে কনফার্ম করা হয়েছে!**\n\n• **অর্ডার নম্বর:** #{$orderNumber}\n• **পণ্য:** {$product->name}\n• **পণ্য মূল্য:** " . BanglaHelper::formatTaka($subtotal) . "\n• **ডেলিভারি চার্জ:** " . BanglaHelper::formatTaka($shippingCost) . "\n• **সর্বমোট প্রদেয় বিল (ক্যাশ অন ডেলিভারি):** " . BanglaHelper::formatTaka($totalAmount) . "\n• **ডেলিভারি ঠিকানা:** {$address}\n• **মোবাইল:** {$phone}\n\nআমাদের ডেলিভারি টিম দ্রুততম সময়ে আপনার পার্সেলটি পাঠিয়ে দেবে। ধন্যবাদ!"
            : "🎉 **Congratulations {$customerName}! Your order has been confirmed successfully via AI!**\n\n• **Order Number:** #{$orderNumber}\n• **Product:** {$product->name}\n• **Price:** " . BanglaHelper::formatTaka($subtotal) . "\n• **Delivery Charge:** " . BanglaHelper::formatTaka($shippingCost) . "\n• **Total Bill (Cash on Delivery):** " . BanglaHelper::formatTaka($totalAmount) . "\n• **Address:** {$address}\n• **Phone:** {$phone}\n\nOur team is packing your package for express dispatch. Thank you!";

        return [
            'reply' => $replyText,
            'type' => 'order_receipt',
            'payload' => [
                'order_id' => $order->id,
                'order_number' => $orderNumber,
                'product_name' => $product->name,
                'total_amount' => $totalAmount,
                'total_formatted' => BanglaHelper::formatTaka($totalAmount),
                'delivery_address' => $address,
                'invoice_url' => route('order.invoice', $orderNumber),
            ],
            'chips' => $isBn ? ['📦 অর্ডার ট্র্যাক করুন', '📄 ইনভয়েস দেখুন', '🛍️ আরও কেনাকাটা করুন'] : ['Track Order', 'View Invoice', 'Shop More'],
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
            '/লোকেশন[:\s]+([^\n,]+)/iu',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                return trim($m[1]);
            }
        }

        $locationWords = ['dhaka', 'ঢাকা', 'chittagong', 'চট্টগ্রাম', 'sylhet', 'সিলেট', 'mirpur', 'মিরপুর', 'uttara', 'উত্তরা', 'dhanmondi', 'ধানমন্ডি', 'gulshan', 'গুলশান', 'gazipur', 'গাজীপুর', 'khulna', 'খুলনা', 'rajshahi', 'রাজশাহী', 'cumilla', 'কুমিল্লা'];
        foreach ($locationWords as $lw) {
            if (str_contains(strtolower($text), $lw)) {
                return trim($text);
            }
        }

        return null;
    }

    /**
     * Generate smart answers for general knowledge questions
     */
    private static function generateSmartKnowledgeReply(string $lower, bool $isBn): array
    {
        if (str_contains($lower, 'delivery') || str_contains($lower, 'ডেলিভারি') || str_contains($lower, 'shipping') || str_contains($lower, 'charge') || str_contains($lower, 'খরচ')) {
            $reply = $isBn 
                ? "📦 **ডেলিভারি চার্জ ও সময়সূচী:**\n\n• **ঢাকার ভিতরে:** ৳৬০ (২৪ ঘণ্টার মধ্যে দ্রুততম এক্সপ্রেস ডেলিভারি)\n• **ঢাকার বাইরে (৬৪ জেলায়):** ৳১২০ (৪৮-৭২ ঘণ্টার মধ্যে হোম ডেলিভারি)\n• **ফ্রি ডেলিভারি:** ৳২,০০০ এর বেশি অর্ডারে `FREESHIPBD` কুপন কোড ব্যবহার করুন!"
                : "📦 **Bangladesh Delivery Policy:**\n\n• **Inside Dhaka:** ৳60 (24h Express Delivery)\n• **Outside Dhaka (All 64 Districts):** ৳120 (48-72h Doorstep Delivery via Steadfast / Pathao)\n• **Free Delivery:** Use coupon `FREESHIPBD` on orders over ৳2,000!";

            $chips = $isBn ? ['🔥 সেরা ডিলস', '🎁 কুপন কোড', '💬 প্রতিনিধির সাথে কথা বলুন'] : ['Best Deals', 'Coupons', 'Talk to Agent'];
        } elseif (str_contains($lower, 'bkash') || str_contains($lower, 'বিকাশ') || str_contains($lower, 'nagad') || str_contains($lower, 'নগদ') || str_contains($lower, 'payment') || str_contains($lower, 'পেমেন্ট') || str_contains($lower, 'cod') || str_contains($lower, 'ক্যাশ')) {
            $reply = $isBn
                ? "💳 **পেমেন্ট পদ্ধতিসমূহ:**\n\n১. **ক্যাশ অন ডেলিভারি (COD):** পার্সেল হাতে পেয়ে চেক করে মূল্য পরিশোধ করুন\n২. **বিকাশ সরাসরি পেমেন্ট (bKash Direct)**\n৩. **নগদ ও রকেট ডিজিটাল পেমেন্ট**\n৪. **ভিসা / মাস্টারকার্ড**"
                : "💳 **Payment Methods Available:**\n\n1. **Cash on Delivery (COD)** nationwide\n2. **bKash Direct Seamless Gateway**\n3. **Nagad & Rocket**\n4. **Visa / Mastercard**";

            $chips = $isBn ? ['📦 ডেলিভারি চার্জ', '🎁 ডিসকাউন্ট অফার', '🎧 সেরা পণ্য'] : ['Delivery Charge', 'Discount Offers', 'Top Products'];
        } elseif (str_contains($lower, 'warranty') || str_contains($lower, 'ওয়ারেন্টি') || str_contains($lower, 'গ্যারান্টি') || str_contains($lower, 'return') || str_contains($lower, 'ফেরত')) {
            $reply = $isBn
                ? "🛡️ **ওয়ারেন্টি ও রিটার্ন পলিসি:**\n\n• **১০০% অরিজিনাল পণ্য:** প্রতিটি গ্যাজেটে অফিশিয়াল রিপ্লেসমেন্ট ওয়ারেন্টি রয়েছে।\n• **৭ দিনের ফ্রি রিটার্ন:** পণ্যতে কোনো ত্রুটি থাকলে ৭ দিনের মধ্যে সরাসরি পরিবর্তন করে দেওয়া হয়।\n• **ডেলিভারির সময় চেক করার সুবিধা:** ডেলিভারিম্যানের সামনে পার্সেল খুলে চেক করে নিতে পারবেন।"
                : "🛡️ **Warranty & Return Policy:**\n\n• **100% Genuine Tech:** All gadgets include official warranty.\n• **7-Day Hassle-Free Return:** Free exchange for any defect.\n• **Parcel Inspection:** Check parcel in front of courier delivery agent before payment.";

            $chips = $isBn ? ['🎧 গ্যাজেট ক্যাটালগ', '📦 অর্ডার ট্র্যাক', '💬 সাপোর্ট এজেন্ট'] : ['Gadget Catalog', 'Track Order', 'Support Agent'];
        } else {
            $reply = $isBn
                ? "🤖 আমি **NEXUS DOKAN** এর এআই সেলস ও সাপোর্ট অ্যাসিস্ট্যান্ট! আমি আপনাকে পণ্যের স্পেসিফিকেশন, দাম, ডিসকাউন্ট কুপন এবং অর্ডার ট্র্যাকিংয়ে সাহায্য করতে পারি।\n\nসরাসরি অর্ডার করতে পছন্দের **পণ্যের নাম, মোবাইল নম্বর ও ঠিকানা** লিখে পাঠান!"
                : "🤖 I am your **NEXUS DOKAN** AI Sales & Support Assistant! Ask me about product specs, pricing, discount coupons, or order tracking.\n\nTo place an order directly, simply write your **Product Name, Phone Number & Address**!";

            $chips = $isBn ? ['🔥 আজকের সেরা ডিলস', '🎧 ANC ইয়ারবাডস', '⌨️ মেকানিক্যাল কিবোর্ড', '📦 ডেলিভারি চার্জ'] : ['Best Deals', 'Earbuds', 'Keyboards', 'Delivery Charges'];
        }

        return ['reply' => $reply, 'type' => 'text', 'payload' => null, 'chips' => $chips];
    }
}
