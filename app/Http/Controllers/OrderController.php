<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Helpers\BanglaHelper;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function success($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view('orders.success', compact('order'));
    }

    public function track(Request $request)
    {
        $order = null;
        $searchPerformed = false;

        if ($request->filled('order_number') || $request->filled('phone')) {
            $searchPerformed = true;
            $query = Order::with('items');

            if ($request->filled('order_number')) {
                $query->where('order_number', trim($request->order_number));
            }

            if ($request->filled('phone')) {
                $query->where('customer_phone', 'like', '%' . trim($request->phone) . '%');
            }

            $order = $query->latest()->first();
        }

        return view('orders.track', compact('order', 'searchPerformed'));
    }

    public function invoice($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view('orders.invoice', compact('order'));
    }
}
