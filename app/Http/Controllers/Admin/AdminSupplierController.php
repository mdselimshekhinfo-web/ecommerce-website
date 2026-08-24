<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierTransaction;
use Illuminate\Http\Request;

class AdminSupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchaseOrders');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('company_name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $suppliers = $query->latest()->paginate(10);
        $totalPayableDue = Supplier::sum('current_due');
        $totalPurchased = Supplier::sum('total_purchased');

        return view('admin.suppliers.index', compact('suppliers', 'totalPayableDue', 'totalPurchased'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
        ]);

        $openingBal = $validated['opening_balance'] ?? 0.00;
        $supplier = Supplier::create([
            'name' => $validated['name'],
            'company_name' => $validated['company_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'opening_balance' => $openingBal,
            'current_due' => $openingBal,
            'status' => 'active',
        ]);

        if ($openingBal > 0) {
            SupplierTransaction::create([
                'supplier_id' => $supplier->id,
                'type' => 'purchase',
                'amount' => $openingBal,
                'notes' => 'Opening Balance',
                'running_balance' => $openingBal,
            ]);
        }

        return redirect()->route('admin.suppliers.index')->with('success', 'নতুন সাপ্লায়ার সফলভাবে যুক্ত হয়েছে! 📦');
    }

    public function show($id)
    {
        $supplier = Supplier::with(['purchaseOrders', 'transactions'])->findOrFail($id);
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function addPayment(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'reference_no' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $newBalance = max(0, $supplier->current_due - $validated['amount']);

        SupplierTransaction::create([
            'supplier_id' => $supplier->id,
            'type' => 'payment',
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference_no' => $validated['reference_no'],
            'notes' => $validated['notes'],
            'running_balance' => $newBalance,
        ]);

        $supplier->recalculateBalances();

        return redirect()->back()->with('success', 'সাপ্লায়ার পেমেন্ট সফলভাবে এন্ট্রি হয়েছে ও লেজার আপডেট হয়েছে! 💵');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'সাপ্লায়ার ডিলিট করা হয়েছে!');
    }
}
