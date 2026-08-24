<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart;
use App\Services\Sms\BangladeshSmsService;
use Illuminate\Http\Request;

class AdminAbandonedCartController extends Controller
{
    public function index(Request $request)
    {
        $query = AbandonedCart::latest();

        if ($request->filled('status')) {
            $query->where('recovery_status', $request->status);
        }

        $carts = $query->paginate(10);
        $totalAbandoned = AbandonedCart::count();
        $potentialRevenue = AbandonedCart::where('recovery_status', 'pending')->sum('subtotal');
        $recoveredCount = AbandonedCart::where('recovery_status', 'recovered')->count();

        return view('admin.abandoned_carts.index', compact('carts', 'totalAbandoned', 'potentialRevenue', 'recoveredCount'));
    }

    public function sendSmsReminder(Request $request, $id)
    {
        $cart = AbandonedCart::findOrFail($id);

        if (!$cart->customer_phone) {
            return redirect()->back()->with('error', 'এই কার্টে কোনো ফোন নম্বর নেই!');
        }

        $msg = "NEXUS DOKAN: প্রিয় {$cart->customer_name}, আপনার কার্টে থাকা সাইবার প্রোডাক্টগুলো অর্ডার সম্পন্ন করতে ভাউচার কোড CYBER10 ব্যবহার করে ১০% ডিসকাউন্ট উপভোগ করুন: " . route('shop.index');

        BangladeshSmsService::sendSms($cart->customer_phone, $msg);
        $cart->update(['recovery_status' => 'contacted', 'recovery_notes' => 'Recovery SMS sent with coupon CYBER10']);

        return redirect()->back()->with('success', 'রিকভারি এসএমএস কুপন সহ কাস্টমারের নম্বরে পাঠানো হয়েছে! 🛒');
    }

    public function updateStatus(Request $request, $id)
    {
        $cart = AbandonedCart::findOrFail($id);
        $cart->update([
            'recovery_status' => $request->recovery_status,
            'recovery_notes' => $request->recovery_notes,
        ]);

        return redirect()->back()->with('success', 'কার্ট স্ট্যাটাস আপডেট হয়েছে!');
    }
}
