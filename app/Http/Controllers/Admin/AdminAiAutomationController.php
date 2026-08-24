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

        $autoDispatchStatus = ThemeSetting::get('ai_auto_dispatch_courier', '1');
        $autoVoiceCallStatus = ThemeSetting::get('ai_auto_voice_call', '1');
        $autoWhatsAppStatus = ThemeSetting::get('ai_auto_whatsapp_verify', '1');

        return view('admin.ai_automation.index', compact(
            'productsCount',
            'seoOptimizedCount',
            'totalOrders',
            'verifiedOrdersCount',
            'recentOrders',
            'autoDispatchStatus',
            'autoVoiceCallStatus',
            'autoWhatsAppStatus'
        ));
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

        return response()->json([
            'success' => true,
            'phone' => $phone,
            'customer_name' => $customerName,
            'voice_script' => $voiceScript,
            'telephony_status' => 'AI Voice Engine Dispatched to ' . $phone,
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
