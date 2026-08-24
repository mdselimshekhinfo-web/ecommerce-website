<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Wishlist;
use App\Helpers\BanglaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $orders = Order::with('items')->where('user_id', $user->id)->latest()->get();
        $totalSpent = $orders->where('payment_status', 'paid')->sum('total_amount');
        $activeOrdersCount = $orders->whereIn('order_status', ['pending', 'processing', 'packed', 'shipped'])->count();

        $wishlistItems = Wishlist::with('product')->where('user_id', $user->id)->get();
        $districts = BanglaHelper::getDistricts();
        $addresses = \App\Models\UserAddress::where('user_id', $user->id)->latest()->get();

        return view('customer.dashboard', compact('user', 'orders', 'totalSpent', 'activeOrdersCount', 'wishlistItems', 'districts', 'addresses'));
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'district' => 'required|string',
            'address' => 'required|string',
        ]);

        \App\Models\UserAddress::create([
            'user_id' => Auth::id(),
            'label' => $validated['label'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'district' => $validated['district'],
            'address' => $validated['address'],
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->back()->with('success', 'নতুন ঠিকানা সফলভাবে সেভ হয়েছে! 📍');
    }

    public function deleteAddress($id)
    {
        $addr = \App\Models\UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $addr->delete();

        return redirect()->back()->with('success', 'ঠিকানা মুছে ফেলা হয়েছে!');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['nullable', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            'district' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'district' => $request->district,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Profile information updated successfully!');
    }

    public function toggleWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();
        $sessionId = session()->getId();

        $query = Wishlist::where('product_id', $productId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = 'Product removed from your wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'session_id' => $sessionId,
            ]);
            $status = 'added';
            $message = 'Product added to your wishlist! ❤️';
        }

        $count = Wishlist::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->count();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message,
                'wishlist_count' => $count,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
