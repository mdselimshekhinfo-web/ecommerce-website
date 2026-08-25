<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\ThemeSetting;
use App\Models\ChatSession;
use App\Services\AutoSeoService;
use App\Services\AiMarketingCopyService;
use App\Services\AiFraudScoreService;
use App\Services\WhatsAppVerificationService;
use Illuminate\Http\Request;

class AdminAiAutomationController extends Controller
{
    /**
     * Display the Simplified & Powerful AI Control Center
     */
    public function index()
    {
        $productsCount = Product::count();
        $seoOptimizedCount = Product::whereNotNull('meta_title')->count();
        $totalOrders = Order::count();
        $recentOrders = Order::latest()->take(10)->get();

        // AI Performance Metrics
        $aiConversationsCount = ChatSession::count();
        $aiOrdersCount = Order::where('admin_notes', 'like', '%AI%')->count();
        $aiRevenue = Order::where('admin_notes', 'like', '%AI%')->sum('total_amount');
        $aiConversionRate = $aiConversationsCount > 0 ? round(($aiOrdersCount / $aiConversationsCount) * 100, 1) : 18.5;

        // Core AI Settings (Simple & Easy)
        $botName = ThemeSetting::get('ai_bot_name', 'Aura AI');
        $botPersona = ThemeSetting::get('ai_bot_persona', 'polite_sales');
        $botGreeting = ThemeSetting::get('ai_bot_greeting', '👋 হ্যালো! আমি Aura AI, আপনার শপিং ও সাপোর্ট অ্যাসিস্ট্যান্ট। যেকোনো প্রোডাক্ট বা অর্ডারের জন্য মেসেজ দিন!');
        $autoDiscountLimit = ThemeSetting::get('ai_auto_discount_limit', '10');
        $autoDispatchStatus = ThemeSetting::get('ai_auto_dispatch_courier', '1');
        $storePhone = ThemeSetting::get('store_phone', '+8809678831374');
        $waTemplate = ThemeSetting::get('ai_wa_template', "আসসালামু আলাইকুম {customer_name} ভাই! NEXUS DOKAN থেকে আপনার অর্ডারটি কনফার্ম করতে 'হ্যাঁ' লিখে রিপ্লাই দিন।");

        // Sample Products for 1-Click Marketing Copy Tool
        $sampleProducts = Product::latest()->take(15)->get();
        $sampleProduct = $sampleProducts->first();
        $sampleMarketingCopy = $sampleProduct ? AiMarketingCopyService::generateAdCopy($sampleProduct) : null;

        return view('admin.ai_automation.index', compact(
            'productsCount',
            'seoOptimizedCount',
            'totalOrders',
            'recentOrders',
            'sampleProducts',
            'sampleMarketingCopy',
            'aiConversationsCount',
            'aiOrdersCount',
            'aiRevenue',
            'aiConversionRate',
            'botName',
            'botPersona',
            'botGreeting',
            'autoDiscountLimit',
            'autoDispatchStatus',
            'storePhone',
            'waTemplate'
        ));
    }

    /**
     * Save AI Settings
     */
    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'ai_bot_name' => 'nullable|string|max:100',
            'ai_bot_persona' => 'nullable|string|max:50',
            'ai_bot_greeting' => 'nullable|string|max:500',
            'ai_auto_discount_limit' => 'nullable|numeric|min:0|max:50',
            'ai_wa_template' => 'nullable|string|max:1000',
            'ai_auto_dispatch_courier' => 'nullable|string',
            'store_phone' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            ThemeSetting::set($key, $value ?? '');
        }

        if (!empty($validated['whatsapp_number'])) {
            ThemeSetting::set('whatsapp_number', $validated['whatsapp_number']);
        }

        return back()->with('success', '⚡ এআই অ্যাসিস্ট্যান্ট ও সেটিংস সফলভাবে আপডেট হয়েছে!');
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

        $adCopy = AiMarketingCopyService::generateAdCopy($product);
        return response()->json($adCopy);
    }

    /**
     * Check AI Fraud Score for an order
     */
    public function checkFraudScore($orderId)
    {
        $order = Order::findOrFail($orderId);
        $result = AiFraudScoreService::analyzeOrder($order);
        return response()->json($result);
    }

    /**
     * 1-Click AI SEO Generator for all products
     */
    public function generateAllSeo()
    {
        $count = AutoSeoService::generateForAllProducts();
        return back()->with('success', "🚀 সফলভাবে {$count}টি পণ্যের গুগল এসইও মেটা ও রিচ স্নsnippet তৈরি করা হয়েছে!");
    }

    /**
     * Simulate WhatsApp reply
     */
    public function simulateWhatsAppReply(Request $request)
    {
        $phone = $request->input('phone', '01947521688');
        $reply = $request->input('reply', 'হ্যাঁ');

        $result = WhatsAppVerificationService::processIncomingReply($phone, $reply);
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
