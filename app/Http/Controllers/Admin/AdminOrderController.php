<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%")
                  ->orWhere('transaction_id', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(12)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show Manual / FB / POS Order Creation Form
     */
    public function create()
    {
        $products = Product::where('status', 'active')->orderBy('name')->get();
        return view('admin.orders.create', compact('products'));
    }

    /**
     * Store Manual Order and auto-deduct inventory stock
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:150',
            'customer_phone' => 'required|string|regex:/^01[3-9]\d{8}$/',
            'delivery_address' => 'required|string|max:500',
            'delivery_district' => 'required|string|max:100',
            'order_channel' => 'required|in:facebook,whatsapp,phone,pos,manual',
            'shipping_cost' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cod,bkash,nagad,card,cash',
            'payment_status' => 'required|in:unpaid,paid',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $channelPrefixes = [
            'facebook' => 'FB-',
            'whatsapp' => 'WA-',
            'phone' => 'PH-',
            'pos' => 'POS-',
            'manual' => 'MO-',
        ];
        $prefix = $channelPrefixes[$request->order_channel] ?? 'MO-';
        $orderNumber = $prefix . strtoupper(Str::random(6));

        $shippingCost = (float) $request->shipping_cost;
        $discountAmount = (float) ($request->discount_amount ?: 0);
        $subtotal = 0;

        // Calculate subtotal & validate stock
        $itemsData = [];
        foreach ($request->items as $itemInput) {
            $product = Product::findOrFail($itemInput['product_id']);
            $qty = (int) $itemInput['quantity'];
            $unitPrice = (float) $product->effective_price;
            $lineTotal = $unitPrice * $qty;
            $subtotal += $lineTotal;

            $itemsData[] = [
                'product' => $product,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];
        }

        $totalAmount = max(0, $subtotal + $shippingCost - $discountAmount);
        $isDhaka = str_contains(strtolower($request->delivery_district), 'dhaka') || str_contains(strtolower($request->delivery_address), 'ঢাকা');

        $channelNames = [
            'facebook' => 'Facebook Messenger / Page',
            'whatsapp' => 'WhatsApp Chat',
            'phone' => 'Direct Phone Call',
            'pos' => 'Store POS / Walk-in',
            'manual' => 'Manual Admin Entry',
        ];

        $sourceNote = "📝 চ্যানেল: " . ($channelNames[$request->order_channel] ?? 'Manual');
        $adminNotes = trim($sourceNote . ($request->admin_notes ? " | " . $request->admin_notes : ''));

        // Create Order
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => null,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email ?: null,
            'delivery_district' => $request->delivery_district,
            'delivery_address' => $request->delivery_address,
            'delivery_notes' => $request->delivery_notes ?: 'Manual / Social Media Order',
            'shipping_zone' => $isDhaka ? 'inside_dhaka' : 'outside_dhaka',
            'shipping_cost' => $shippingCost,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'order_status' => 'processing',
            'verification_status' => in_array($request->order_channel, ['facebook', 'whatsapp', 'phone']) ? 'whatsapp_verified' : 'unverified',
            'admin_notes' => $adminNotes,
        ]);

        // Create Order Items and Auto-Deduct Inventory Stock
        foreach ($itemsData as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'product_name' => $item['product']->name,
                'variant' => null,
                'price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'total' => $item['line_total'],
            ]);

            // Auto Decrement Stock
            $item['product']->decrement('stock_quantity', $item['quantity']);
            $item['product']->increment('sales_count', $item['quantity']);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', "🎉 ম্যানুয়াল অর্ডার #{$orderNumber} সফলভাবে তৈরি হয়েছে এবং স্টক স্বয়ংক্রিয়ভাবে সমন্বয় করা হয়েছে!");
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        $oldStatus = $order->order_status;

        $request->validate([
            'order_status' => 'required|in:pending,processing,packed,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded,unpaid',
            'courier_name' => 'nullable|string',
            'tracking_code' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        $newStatus = $request->order_status;

        // Auto Stock Restoration if Order Cancelled
        if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                    $item->product->decrement('sales_count', $item->quantity);
                }
            }
        } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            // Re-deduct stock if un-cancelled
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                    $item->product->increment('sales_count', $item->quantity);
                }
            }
        }

        $order->update($request->only([
            'order_status',
            'payment_status',
            'courier_name',
            'tracking_code',
            'transaction_id',
            'admin_notes',
        ]));

        return redirect()->back()->with('success', "Order #{$order->order_number} status updated successfully!");
    }
}
