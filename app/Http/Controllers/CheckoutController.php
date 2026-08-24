<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Helpers\BanglaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your shopping cart is empty! Add products before checkout.');
        }

        $districts = BanglaHelper::getDistricts();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $coupon = session()->get('coupon');
        $discount = 0;
        if ($coupon) {
            $couponModel = Coupon::where('code', $coupon['code'])->first();
            if ($couponModel && $couponModel->isValidForAmount($subtotal)) {
                $discount = $couponModel->calculateDiscount($subtotal);
            }
        }

        $defaultShipping = 60; // Dhaka default

        return view('checkout.index', compact('cart', 'districts', 'subtotal', 'discount', 'coupon', 'defaultShipping'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => ['required', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            'customer_email' => 'nullable|email',
            'delivery_district' => 'required|string',
            'delivery_address' => 'required|string|min:10',
            'delivery_notes' => 'nullable|string',
            'payment_method' => 'required|in:cod,bkash,nagad,rocket,card',
            'bkash_trx_id' => 'nullable|string',
            'nagad_trx_id' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $districts = BanglaHelper::getDistricts();
        $districtInfo = $districts[$request->delivery_district] ?? ['zone' => 'outside_dhaka', 'cost' => 120];

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $coupon = session()->get('coupon');
        $discount = 0;
        $couponCode = null;

        if ($coupon) {
            $couponModel = Coupon::where('code', $coupon['code'])->first();
            if ($couponModel && $couponModel->isValidForAmount($subtotal)) {
                $discount = $couponModel->calculateDiscount($subtotal);
                $couponCode = $couponModel->code;
                $couponModel->increment('used_count');
            }
        }

        $shippingCost = (float) $districtInfo['cost'];
        $totalAmount = max(0, $subtotal - $discount) + $shippingCost;

        // Payment status & Transaction ID handling
        $paymentStatus = 'pending';
        $transactionId = null;

        if ($request->payment_method === 'bkash') {
            $paymentStatus = 'paid';
            $transactionId = $request->bkash_trx_id ?: 'BKS' . strtoupper(Str::random(8));
        } elseif ($request->payment_method === 'nagad') {
            $paymentStatus = 'paid';
            $transactionId = $request->nagad_trx_id ?: 'NGD' . strtoupper(Str::random(8));
        } elseif ($request->payment_method === 'rocket') {
            $paymentStatus = 'paid';
            $transactionId = 'RKT' . strtoupper(Str::random(8));
        } elseif ($request->payment_method === 'card') {
            $paymentStatus = 'paid';
            $transactionId = 'CRD' . strtoupper(Str::random(8));
        }

        $orderNumber = 'NX-' . date('Y') . '-' . strtoupper(Str::random(6));

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'delivery_district' => $request->delivery_district,
            'delivery_address' => $request->delivery_address,
            'delivery_notes' => $request->delivery_notes,
            'shipping_zone' => $districtInfo['zone'],
            'shipping_cost' => $shippingCost,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'coupon_code' => $couponCode,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => $paymentStatus,
            'transaction_id' => $transactionId,
            'order_status' => 'pending',
            'courier_name' => $districtInfo['zone'] === 'inside_dhaka' ? 'Pathao Express' : 'Steadfast Courier BD',
            'tracking_code' => 'TRK-' . strtoupper(Str::random(8)),
            'admin_notes' => 'New Order received via Web Checkout.',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_image' => $item['thumbnail'] ?? null,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'variant_info' => $item['variant'] ?? 'Default',
                'total' => $item['total'],
            ]);
        }

        // Clear cart session
        session()->forget('cart');
        session()->forget('coupon');

        return redirect()->route('order.success', $order->order_number)
            ->with('success', 'Your order has been placed successfully! ধন্যবাদ!');
    }
}
