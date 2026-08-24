<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Courier\SteadfastService;
use Illuminate\Http\Request;

class AdminCourierController extends Controller
{
    public function bookConsignment(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $res = SteadfastService::createConsignment($order);

        return redirect()->back()->with('success', 'অর্ডারটি সফলভাবে কুরিয়ারে বুক করা হয়েছে! ট্র্যাকিং কোড: ' . $res['tracking_code'] . ' 🚚');
    }

    public function printLabel($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        return view('admin.orders.courier_label', compact('order'));
    }
}
