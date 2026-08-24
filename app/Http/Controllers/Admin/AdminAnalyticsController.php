<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller
{
    public function pnl()
    {
        $paidOrders = Order::with('items.product')->where('payment_status', 'paid')->get();

        $totalRevenue = $paidOrders->sum('total_amount');
        $totalShippingCollected = $paidOrders->sum('shipping_cost');
        $totalDiscounts = $paidOrders->sum('discount_amount');

        // Calculate Cost of Goods Sold (COGS)
        $totalCogs = 0;
        foreach ($paidOrders as $order) {
            foreach ($order->items as $item) {
                $cost = $item->product ? ($item->product->cost_price ?: ($item->price * 0.65)) : ($item->price * 0.65);
                $totalCogs += ($cost * $item->quantity);
            }
        }

        // Net Profit Calculation
        $netProfit = $totalRevenue - $totalCogs - ($totalShippingCollected * 0.8) - $totalDiscounts;
        $profitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 1) : 0;

        // Top 64 Districts Sales Breakdown
        $districtStats = Order::select('delivery_district', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total_sales'))
            ->groupBy('delivery_district')
            ->orderBy('total_sales', 'desc')
            ->take(6)
            ->get();

        return view('admin.analytics.pnl', compact(
            'totalRevenue',
            'totalCogs',
            'totalDiscounts',
            'totalShippingCollected',
            'netProfit',
            'profitMargin',
            'districtStats'
        ));
    }

    public function exportOrders()
    {
        $orders = Order::latest()->get();
        $csvFileName = 'nexus_orders_export_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Order Number', 'Date', 'Customer Name', 'Phone', 'District', 'Payment Method', 'Payment Status', 'Delivery Status', 'Total (BDT)'];

        $callback = function () use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($file, $columns);

            foreach ($orders as $o) {
                fputcsv($file, [
                    $o->order_number,
                    $o->created_at->format('Y-m-d H:i'),
                    $o->customer_name,
                    $o->customer_phone,
                    $o->delivery_district,
                    $o->payment_method,
                    $o->payment_status,
                    $o->order_status,
                    $o->total_amount,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
