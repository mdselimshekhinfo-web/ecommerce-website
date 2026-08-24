<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

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

        $customers = $query->latest()->paginate(10);
        $totalCustomers = User::where('role', 'customer')->count();

        return view('admin.customers.index', compact('customers', 'totalCustomers'));
    }

    public function show($id)
    {
        $customer = User::with('orders.items')->findOrFail($id);
        $totalSpent = $customer->orders()->where('payment_status', 'paid')->sum('total_amount');

        return view('admin.customers.show', compact('customer', 'totalSpent'));
    }
}
