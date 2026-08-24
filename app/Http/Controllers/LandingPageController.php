<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\ThemeSetting;
use App\Services\Sms\BangladeshSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    public function show($slug)
    {
        $landingPage = LandingPage::with('product')->where('slug', $slug)->where('status', 'active')->firstOrFail();
        
        $reviews = [];
        if ($landingPage->product_id) {
            $reviews = ProductReview::where('product_id', $landingPage->product_id)
                ->where('status', 'approved')
                ->latest()
                ->take(6)
                ->get();
        }

        $settings = ThemeSetting::pluck('value', 'key')->toArray();

        return view('landing.show', compact('landingPage', 'reviews', 'settings'));
    }

    public function processDirectOrder(Request $request, $slug)
    {
        $landingPage = LandingPage::with('product')->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|min:11|max:15',
            'delivery_district' => 'required|string',
            'delivery_address' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'variant' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $landingPage, $validated) {
            $shippingCost = (trim(strtolower($validated['delivery_district'])) === 'dhaka') ? 60.00 : 120.00;
            $qty = $validated['quantity'];
            $subtotal = $landingPage->offer_price * $qty;
            $totalAmount = $subtotal + $shippingCost;

            $orderNumber = 'NX-' . date('Y') . '-' . strtoupper(Str::random(6));

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->id() ?? null,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $request->customer_email ?: null,
                'delivery_district' => $validated['delivery_district'],
                'delivery_address' => $validated['delivery_address'],
                'delivery_notes' => '1-Page Landing Funnel Order (' . $landingPage->title . ')',
                'shipping_zone' => (trim(strtolower($validated['delivery_district'])) === 'dhaka') ? 'Inside Dhaka' : 'Outside Dhaka',
                'shipping_cost' => $shippingCost,
                'subtotal' => $subtotal,
                'discount_amount' => 0.00,
                'total_amount' => $totalAmount,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'customer_risk_score' => 'low',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $landingPage->product_id,
                'product_name' => $landingPage->product ? $landingPage->product->name : $landingPage->title,
                'variant_info' => $validated['variant'] ?: 'Standard Edition',
                'price' => $landingPage->offer_price,
                'quantity' => $qty,
                'total' => $subtotal,
                'product_image' => $landingPage->banner_image ?: ($landingPage->product ? $landingPage->product->thumbnail : null),
            ]);

            // Decrement product stock if available
            if ($landingPage->product) {
                $landingPage->product->decrement('stock_quantity', min($landingPage->product->stock_quantity, $qty));
            }

            // Send instant confirmation SMS
            BangladeshSmsService::sendOrderConfirmation($order);

            return redirect()->route('order.success', $order->order_number)
                ->with('success', 'আপনার অর্ডারটি সফলভাবে গৃহীত হয়েছে! 🇧🇩');
        });
    }
}
