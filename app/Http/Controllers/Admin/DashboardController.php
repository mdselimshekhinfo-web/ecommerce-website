<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\AbandonedCart;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Today's Live Pulse
        $todaySales = Order::whereDate('created_at', $today)->where('payment_status', 'paid')->sum('total_amount');
        $todayOrdersCount = Order::whereDate('created_at', $today)->count();
        $todayDeliveredCount = Order::whereDate('updated_at', $today)->where('order_status', 'delivered')->count();
        $pendingDispatchCount = Order::whereIn('order_status', ['pending', 'processing'])->count();

        // 2. Courier & Logistics Pipeline
        $inTransitCount = Order::where('order_status', 'shipped')->count();
        $pendingCodReceivable = Order::where('payment_method', 'cod')
            ->where('payment_status', '!=', 'paid')
            ->whereIn('order_status', ['shipped', 'processing'])
            ->sum('total_amount');

        $deliveredTotal = Order::where('order_status', 'delivered')->count();
        $cancelledTotal = Order::where('order_status', 'cancelled')->count();
        $deliverySuccessRate = ($deliveredTotal + $cancelledTotal) > 0 
            ? round(($deliveredTotal / ($deliveredTotal + $cancelledTotal)) * 100, 1) 
            : 96.5;

        // 3. Overall Totals
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalSupplierDue = Supplier::sum('current_due');

        // 4. Low Stock Emergency Warning List
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)->take(4)->get();
        $lowStockCount = Product::where('stock_quantity', '<=', 5)->count();

        // 5. Top Selling Products
        $topProducts = Product::orderBy('sales_count', 'desc')->take(5)->get();

        // 6. Recent Orders
        $recentOrders = Order::with('items')->latest()->take(6)->get();

        // 7. Recent Abandoned Carts for 1-Click WhatsApp Follow-up
        $recentAbandoned = AbandonedCart::where('recovery_status', 'pending')->latest()->take(3)->get();

        // 8. 7-Day Revenue & Net Profit Trend Data for Neon Chart
        $days = [];
        $salesData = [];
        $profitData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayLabel = Carbon::now()->subDays($i)->format('D');
            $days[] = $dayLabel;
            
            $daySales = Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
            
            $salesData[] = (float) $daySales;
            $profitData[] = round((float) $daySales * 0.32, 2); // Est. 32% net margin
        }

        // 9. District Sales Share
        $topDistricts = Order::select('delivery_district', DB::raw('count(*) as order_count'), DB::raw('sum(total_amount) as sales'))
            ->groupBy('delivery_district')
            ->orderBy('sales', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todaySales',
            'todayOrdersCount',
            'todayDeliveredCount',
            'pendingDispatchCount',
            'inTransitCount',
            'pendingCodReceivable',
            'deliverySuccessRate',
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'totalSupplierDue',
            'lowStockProducts',
            'lowStockCount',
            'topProducts',
            'recentOrders',
            'recentAbandoned',
            'days',
            'salesData',
            'profitData',
            'topDistricts'
        ));
    }
}
