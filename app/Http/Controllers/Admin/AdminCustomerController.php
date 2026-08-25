<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(10);
        $totalCustomers = User::where('role', 'customer')->count();
        $activeCustomers = User::where('role', 'customer')->where('status', 'active')->count();

        return view('admin.customers.index', compact('customers', 'totalCustomers', 'activeCustomers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|regex:/^01[3-9]\d{8}$/|unique:users,phone',
            'district' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'password' => 'nullable|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'district' => $validated['district'],
            'address' => $validated['address'],
            'role' => 'customer',
            'status' => 'active',
            'password' => Hash::make($validated['password'] ?: '123456'),
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'নতুন গ্রাহক প্রোফাইল সফলভাবে তৈরি হয়েছে! 👤');
    }

    public function update(Request $request, $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|regex:/^01[3-9]\d{8}$/|unique:users,phone,' . $id,
            'district' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'status' => 'required|in:active,blocked',
            'password' => 'nullable|string|min:6',
        ]);

        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        $customer->phone = $validated['phone'];
        $customer->district = $validated['district'];
        $customer->address = $validated['address'];
        $customer->status = $validated['status'];

        if (!empty($validated['password'])) {
            $customer->password = Hash::make($validated['password']);
        }

        $customer->save();

        return redirect()->back()->with('success', 'গ্রাহকের তথ্য সফলভাবে আপডেট হয়েছে!');
    }

    public function toggleStatus($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->status = ($customer->status === 'blocked') ? 'active' : 'blocked';
        $customer->save();

        $msg = $customer->status === 'blocked' ? 'গ্রাহক একাউন্টটি সাময়িকভাবে সাসপেন্ড/ব্লক করা হয়েছে!' : 'গ্রাহক একাউন্টটি পুনরায় অ্যাক্টিভ করা হয়েছে!';
        return redirect()->back()->with('success', $msg);
    }

    public function show($id)
    {
        $customer = User::with('orders.items')->findOrFail($id);
        $totalSpent = $customer->orders()->where('payment_status', 'paid')->sum('total_amount');
        $ordersCount = $customer->orders()->count();
        $avgOrderValue = $ordersCount > 0 ? round($totalSpent / $ordersCount, 2) : 0;

        return view('admin.customers.show', compact('customer', 'totalSpent', 'ordersCount', 'avgOrderValue'));
    }
}
