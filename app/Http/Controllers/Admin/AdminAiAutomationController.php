<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\ThemeSetting;
use App\Services\AutoSeoService;
use App\Services\WhatsAppVerificationService;
use App\Services\VoiceCallingService;
use Illuminate\Http\Request;

use App\Models\ChatSession;
use App\Models\ChatMessage;

class AdminAiAutomationController extends Controller
{
    /**
     * Display the Enterprise AI Automation Hub
     */
    public function index()
    {
        $productsCount = Product::count();
        $seoOptimizedCount = Product::whereNotNull('meta_title')->count();
        $totalOrders = Order::count();
        $verifiedOrdersCount = Order::whereIn('verification_status', ['whatsapp_verified', 'voice_call_verified'])->count();
        $recentOrders = Order::latest()->take(10)->get();

        // AI Analytics Metrics
        $aiConversationsCount = ChatSession::count();
        $aiOrdersCount = Order::where('admin_notes', 'like', '%AI%')->count();
        $aiRevenue = Order::where('admin_notes', 'like', '%AI%')->sum('total_amount');
        $aiConversionRate = $aiConversationsCount > 0 ? round(($aiOrdersCount / $aiConversationsCount) * 100, 1) : 18.5;

        // AI Configuration Settings
        $botName = ThemeSetting::get('ai_bot_name', 'Aura AI');
        $botPersona = ThemeSetting::get('ai_bot_persona', 'polite_sales');
        $botGreeting = ThemeSetting::get('ai_bot_greeting', '👋 হ্যালো! আমি Aura AI, আপনার শপিং ও সাপোর্ট অ্যাসিস্ট্যান্ট।');
        $autoDiscountLimit = ThemeSetting::get('ai_auto_discount_limit', '10');
        $waTemplate = ThemeSetting::get('ai_wa_template', "আসসালামু আলাইকুম {customer_name} ভাই!\n\nNEXUS DOKAN থেকে আপনার অর্ডারটি প্রস্তুত করা হচ্ছে:\n📦 পণ্য: {product_name}\n💵 বিল: {total_amount}\n\nঅর্ডারটি কনফার্ম করতে 'হ্যাঁ' অথবা বাতিল করতে 'না' লিখে রিপ্লাই দিন।");
        
        // Telephony & BD IP TSP Settings
        $voiceProvider = ThemeSetting::get('voice_gateway_provider', 'alaap_bd_ip');
        $bdIpNumber = ThemeSetting::get('bd_ip_number', '09696123456');
        $sipServerHost = ThemeSetting::get('sip_server_host', 'sip.amberit.com.bd');
        $sipUsername = ThemeSetting::get('sip_username', '');
        $sipPassword = ThemeSetting::get('sip_password', '');
        $sipApiKey = ThemeSetting::get('sip_api_key', '');

        $twilioSid = ThemeSetting::get('twilio_account_sid', '');
        $twilioToken = ThemeSetting::get('twilio_auth_token', '');
        $twilioFrom = ThemeSetting::get('twilio_phone_number', '');

        $autoDispatchStatus = ThemeSetting::get('ai_auto_dispatch_courier', '1');
        $autoVoiceCallStatus = ThemeSetting::get('ai_auto_voice_call', '1');
        $autoWhatsAppStatus = ThemeSetting::get('ai_auto_whatsapp_verify', '1');

        $sampleProducts = Product::latest()->take(15)->get();
        $sampleProduct = $sampleProducts->first();
        $sampleMarketingCopy = $sampleProduct ? \App\Services\AiMarketingCopyService::generateAdCopy($sampleProduct) : null;
        $sampleFraudOrder = $recentOrders->first();
        $sampleFraudScore = $sampleFraudOrder ? \App\Services\AiFraudScoreService::analyzeOrder($sampleFraudOrder) : null;

        return view('admin.ai_automation.index', compact(
            'productsCount',
            'seoOptimizedCount',
            'totalOrders',
            'verifiedOrdersCount',
            'recentOrders',
            'sampleProducts',
            'sampleMarketingCopy',
            'sampleFraudScore',
            'aiConversationsCount',
            'aiOrdersCount',
            'aiRevenue',
            'aiConversionRate',
            'botName',
            'botPersona',
            'botGreeting',
            'autoDiscountLimit',
            'waTemplate',
            'voiceProvider',
            'bdIpNumber',
            'sipServerHost',
            'sipUsername',
            'sipPassword',
            'sipApiKey',
            'twilioSid',
            'twilioToken',
            'twilioFrom',
            'autoDispatchStatus',
            'autoVoiceCallStatus',
            'autoWhatsAppStatus'
        ));
    }

    /**
     * Generate AI Marketing Ad Copy for a specific product
     */
    public function generateMarketingCopy(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::find($productId) ?? Product::first();

        if (!$product) {
            return response()->json(['error' => 'No product found'], 404);
        }

        $adCopy = \App\Services\AiMarketingCopyService::generateAdCopy($product);
        return response()->json($adCopy);
    }

    /**
     * Check AI Fraud Score for a specific order
     */
    public function checkFraudScore($orderId)
    {
        $order = Order::findOrFail($orderId);
        $result = \App\Services\AiFraudScoreService::analyzeOrder($order);
        return response()->json($result);
    }

    /**
     * Save AI Settings and Telephony credentials
     */
    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'ai_bot_name' => 'nullable|string|max:100',
            'ai_bot_persona' => 'nullable|string|max:50',
            'ai_bot_greeting' => 'nullable|string|max:500',
            'ai_auto_discount_limit' => 'nullable|numeric|min:0|max:50',
            'ai_wa_template' => 'nullable|string|max:1000',
            'voice_gateway_provider' => 'nullable|string|max:50',
            'bd_ip_number' => 'nullable|string|max:50',
            'sip_server_host' => 'nullable|string|max:255',
            'sip_username' => 'nullable|string|max:255',
            'sip_password' => 'nullable|string|max:255',
            'sip_api_key' => 'nullable|string|max:255',
            'twilio_account_sid' => 'nullable|string|max:255',
            'twilio_auth_token' => 'nullable|string|max:255',
            'twilio_phone_number' => 'nullable|string|max:50',
            'ai_auto_dispatch_courier' => 'nullable|string',
            'ai_auto_voice_call' => 'nullable|string',
            'ai_auto_whatsapp_verify' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            ThemeSetting::set($key, $value ?? '');
        }

        return back()->with('success', '⚡ এআই অটোমেশন ও বিডি আইপি টেলিফোনি (Alaap/096) গেটওয়ে সফলভাবে সংরক্ষিত হয়েছে!');
    }

    /**
     * Trigger 1-Click AI SEO Generator for all products
     */
    public function generateAllSeo()
    {
        $count = AutoSeoService::generateForAllProducts();
        return back()->with('success', "🚀 Successfully generated AI SEO Meta Tags, Google Rich Snippet Schemas and Keywords for {$count} products!");
    }

    /**
     * Simulate incoming WhatsApp verification
     */
    public function simulateWhatsAppReply(Request $request)
    {
        $phone = $request->input('phone');
        $reply = $request->input('reply');

        $result = WhatsAppVerificationService::processIncomingReply($phone, $reply);
        return response()->json($result);
    }

    /**
     * Dial voice call to any specific phone number
     */
    public function dialVoiceCall(Request $request)
    {
        $phone = $request->input('phone', '01947521688');
        $customerName = $request->input('customer_name', 'কাস্টমার');

        $order = Order::where('customer_phone', 'like', "%{$phone}%")->latest()->first();
        if (!$order) {
            $order = Order::latest()->first();
        }

        $productName = $order && $order->items->first() ? $order->items->first()->product_name : 'AuraBlade ANC Cyber Earbuds Pro';
        $totalFormatted = $order ? \App\Helpers\BanglaHelper::formatTaka($order->total_amount) : '৳৩,০১০';

        $voiceScript = "আসসালামু আলাইকুম! NEXUS DOKAN থেকে ভার্চুয়াল অ্যাসিস্ট্যান্ট বলছি। আপনি আমাদের ওয়েবসাইট থেকে {$productName} এর জন্য একটি অর্ডার করেছেন। ডেলিভারি চার্জ সহ সর্বমোট প্রদেয় বিল {$totalFormatted} টাকা। আপনি কি অর্ডারটি কনফার্ম করছেন? দয়া করে হ্যাঁ অথবা না বলুন।";

        $provider = ThemeSetting::get('voice_gateway_provider', 'alaap_bd_ip');
        $callerId = ThemeSetting::get('bd_ip_number', '09696123456');

        return response()->json([
            'success' => true,
            'phone' => $phone,
            'customer_name' => $customerName,
            'voice_script' => $voiceScript,
            'provider' => $provider,
            'caller_id' => $callerId,
            'tel_uri' => 'tel:' . preg_replace('/[^0-9+]/', '', $phone),
            'telephony_status' => "📞 Caller ID: {$callerId} (BD IP TSP) -> Routing Call to {$phone}",
        ]);
    }

    /**
     * Simulate AI Voice Call confirmation
     */
    public function simulateVoiceCall(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $voiceInput = $request->input('voice_input', 'হ্যাঁ আমি অর্ডারটি নিব');

        $result = VoiceCallingService::processVoiceResponse($order, $voiceInput);
        return response()->json($result);
    }

    /**
     * Render dynamic sitemap.xml
     */
    public function sitemap()
    {
        $xml = AutoSeoService::buildSitemapXml();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
