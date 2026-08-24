<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\SupplierTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchaseOrders = $query->latest()->paginate(10);
        $totalPOs = PurchaseOrder::count();
        $totalPOAmount = PurchaseOrder::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalDue = PurchaseOrder::where('status', '!=', 'cancelled')->sum('due_amount');

        return view('admin.purchase_orders.index', compact('purchaseOrders', 'totalPOs', 'totalPOAmount', 'totalDue'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();
        $nextPONumber = PurchaseOrder::generatePONumber();

        return view('admin.purchase_orders.create', compact('suppliers', 'products', 'nextPONumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.product_name' => 'required|string',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_cost' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,ordered,received',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['unit_cost'] * $item['quantity']);
            }

            $shipping = $request->shipping_cost ?? 0.00;
            $totalAmount = $subtotal + $shipping;
            $paidAmount = $request->paid_amount ?? 0.00;
            $dueAmount = max(0, $totalAmount - $paidAmount);

            $paymentStatus = 'unpaid';
            if ($paidAmount >= $totalAmount) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partial';
            }

            $po = PurchaseOrder::create([
                'po_number' => PurchaseOrder::generatePONumber(),
                'supplier_id' => $request->supplier_id,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'status' => $request->status,
                'payment_status' => $paymentStatus,
                'notes' => $request->notes,
                'received_at' => $request->status === 'received' ? now() : null,
            ]);

            foreach ($request->items as $it) {
                $lineSubtotal = $it['unit_cost'] * $it['quantity'];
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $it['product_id'] ?? null,
                    'product_name' => $it['product_name'],
                    'unit_cost' => $it['unit_cost'],
                    'quantity' => $it['quantity'],
                    'subtotal' => $lineSubtotal,
                ]);

                // If received immediately, update product stock & cost price
                if ($request->status === 'received' && !empty($it['product_id'])) {
                    $prod = Product::find($it['product_id']);
                    if ($prod) {
                        $prod->increment('stock_quantity', $it['quantity']);
                        $prod->update(['cost_price' => $it['unit_cost']]);
                    }
                }
            }

            // Ledger Transaction
            SupplierTransaction::create([
                'supplier_id' => $po->supplier_id,
                'purchase_order_id' => $po->id,
                'type' => 'purchase',
                'amount' => $totalAmount,
                'notes' => 'Purchase Order #' . $po->po_number,
                'running_balance' => $totalAmount,
            ]);

            if ($paidAmount > 0) {
                SupplierTransaction::create([
                    'supplier_id' => $po->supplier_id,
                    'purchase_order_id' => $po->id,
                    'type' => 'payment',
                    'amount' => $paidAmount,
                    'payment_method' => 'bank',
                    'notes' => 'Initial payment for ' . $po->po_number,
                    'running_balance' => $dueAmount,
                ]);
            }

            $po->supplier->recalculateBalances();

            return redirect()->route('admin.purchase_orders.show', $po->id)
                ->with('success', 'নতুন পারচেজ অর্ডার সফলভাবে তৈরি হয়েছে! 📄');
        });
    }

    public function show($id)
    {
        $po = PurchaseOrder::with(['supplier', 'items.product', 'transactions'])->findOrFail($id);
        return view('admin.purchase_orders.show', compact('po'));
    }

    public function receive($id)
    {
        $po = PurchaseOrder::with('items.product')->findOrFail($id);

        if ($po->status === 'received') {
            return redirect()->back()->with('error', 'এই পারচেজ অর্ডারটি ইতিমধ্যে রিসিভ করা হয়েছে!');
        }

        DB::transaction(function () use ($po) {
            $po->update([
                'status' => 'received',
                'received_at' => now(),
            ]);

            // Auto-replenish product inventory stock
            foreach ($po->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                    $item->product->update(['cost_price' => $item->unit_cost]);
                }
            }
        });

        return redirect()->back()->with('success', 'মাল সফলভাবে গোডাউনে রিসিভ হয়েছে এবং ইনভেন্টরি স্টক বৃদ্ধি পেয়েছে! 📦🎉');
    }
}
