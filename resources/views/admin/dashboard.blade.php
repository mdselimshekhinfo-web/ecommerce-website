@extends('layouts.admin')

@section('page-title', \App\Helpers\LocalizationHelper::get('admin_dashboard'))

@section('content')
<div class="space-y-8">

    <!-- 1. TODAY'S LIVE OPERATIONS PULSE (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Today's Sales -->
        <div class="admin-glass rounded-2xl p-5 border border-cyan-500/30 space-y-1 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 font-mono text-[10px] uppercase font-bold tracking-wider">{{ \App\Helpers\LocalizationHelper::get('today_sales') }}</span>
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
            </div>
            <p class="font-cyber font-bold text-2xl text-cyan-300">{{ \App\Helpers\BanglaHelper::formatTaka($todaySales) }}</p>
            <p class="text-[10px] text-slate-400 font-mono">{{ \App\Helpers\LocalizationHelper::get('today_sales_sub') }}</p>
        </div>

        <!-- Today's Orders -->
        <div class="admin-glass rounded-2xl p-5 border border-pink-500/30 space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 font-mono text-[10px] uppercase font-bold tracking-wider">{{ \App\Helpers\LocalizationHelper::get('today_orders') }}</span>
                <i data-lucide="shopping-bag" class="w-4 h-4 text-pink-400"></i>
            </div>
            <p class="font-cyber font-bold text-2xl text-white">{{ $todayOrdersCount }}</p>
            <p class="text-[10px] text-slate-400 font-mono">{{ \App\Helpers\LocalizationHelper::get('today_orders_sub') }}</p>
        </div>

        <!-- Pending Dispatch -->
        <div class="admin-glass rounded-2xl p-5 border border-amber-500/30 space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 font-mono text-[10px] uppercase font-bold tracking-wider">{{ \App\Helpers\LocalizationHelper::get('pending_dispatch') }}</span>
                <i data-lucide="package" class="w-4 h-4 text-amber-400"></i>
            </div>
            <p class="font-cyber font-bold text-2xl text-amber-300">{{ $pendingDispatchCount }}</p>
            <p class="text-[10px] text-slate-400 font-mono">{{ \App\Helpers\LocalizationHelper::get('pending_dispatch_sub') }}</p>
        </div>

        <!-- Delivered Today -->
        <div class="admin-glass rounded-2xl p-5 border border-emerald-500/30 space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 font-mono text-[10px] uppercase font-bold tracking-wider">{{ \App\Helpers\LocalizationHelper::get('delivered_today') }}</span>
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
            </div>
            <p class="font-cyber font-bold text-2xl text-emerald-300">{{ $todayDeliveredCount }}</p>
            <p class="text-[10px] text-slate-400 font-mono">{{ \App\Helpers\LocalizationHelper::get('delivered_today_sub') }}</p>
        </div>

    </div>

    <!-- 2. COURIER LOGISTICS & CASH FLOW (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- In Transit -->
        <div class="admin-glass rounded-2xl p-5 border border-slate-800 space-y-1">
            <span class="text-slate-500 font-mono text-[10px] uppercase">{{ \App\Helpers\LocalizationHelper::get('in_transit') }}</span>
            <p class="font-mono font-black text-xl text-white">{{ $inTransitCount }}</p>
            <p class="text-[10px] text-slate-400">{{ \App\Helpers\LocalizationHelper::get('in_transit_sub') }}</p>
        </div>

        <!-- Pending COD Receivable -->
        <div class="admin-glass rounded-2xl p-5 border border-emerald-500/40 bg-emerald-950/20 space-y-1">
            <span class="text-emerald-400 font-mono text-[10px] uppercase font-bold">{{ \App\Helpers\LocalizationHelper::get('pending_cod') }}</span>
            <p class="font-mono font-black text-xl text-emerald-300">{{ \App\Helpers\BanglaHelper::formatTaka($pendingCodReceivable) }}</p>
            <p class="text-[10px] text-slate-400 font-mono">{{ \App\Helpers\LocalizationHelper::get('pending_cod_sub') }}</p>
        </div>

        <!-- Delivery Success Rate -->
        <div class="admin-glass rounded-2xl p-5 border border-slate-800 space-y-1">
            <span class="text-slate-500 font-mono text-[10px] uppercase">{{ \App\Helpers\LocalizationHelper::get('delivery_rate') }}</span>
            <p class="font-cyber font-bold text-xl text-cyan-300">{{ $deliverySuccessRate }}%</p>
            <p class="text-[10px] text-slate-400">{{ \App\Helpers\LocalizationHelper::get('delivery_rate_sub') }}</p>
        </div>

        <!-- Supplier Payable Due -->
        <div class="admin-glass rounded-2xl p-5 border border-pink-500/30 space-y-1">
            <span class="text-pink-400 font-mono text-[10px] uppercase font-bold">{{ \App\Helpers\LocalizationHelper::get('supplier_due') }}</span>
            <p class="font-mono font-black text-xl text-pink-400">{{ \App\Helpers\BanglaHelper::formatTaka($totalSupplierDue) }}</p>
            <p class="text-[10px] text-slate-400">{{ \App\Helpers\LocalizationHelper::get('supplier_due_sub') }}</p>
        </div>

    </div>

    <!-- 3. MIDDLE ROW: 7-Day Chart & Low Stock / WhatsApp Recovery -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: 7-Day Revenue & Profit Neon Chart (7 Cols) -->
        <div class="lg:col-span-7 admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div>
                    <h3 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('sales_profit_chart') }}</h3>
                    <p class="text-[11px] text-slate-400 font-mono">{{ \App\Helpers\LocalizationHelper::get('chart_subtitle') }}</p>
                </div>
                <div class="flex items-center space-x-3 text-[11px] font-mono">
                    <span class="flex items-center space-x-1 text-cyan-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('revenue') }}</span>
                    </span>
                    <span class="flex items-center space-x-1 text-emerald-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('net_profit') }}</span>
                    </span>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="h-64 w-full">
                <canvas id="revenueProfitChart"></canvas>
            </div>
        </div>

        <!-- Right: Low Stock Alert & 1-Click WhatsApp Booster (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Low Stock Warning Box -->
            <div class="admin-glass rounded-3xl p-5 border border-pink-500/30 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                    <div class="flex items-center space-x-2 text-pink-400">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                        <h4 class="font-cyber font-bold text-xs uppercase">{{ \App\Helpers\LocalizationHelper::get('low_stock_warning') }} ({{ $lowStockCount }})</h4>
                    </div>
                    <a href="{{ route('admin.purchase_orders.create') }}" class="text-[10px] text-cyan-400 font-mono hover:underline">{{ \App\Helpers\LocalizationHelper::get('reorder_now') }}</a>
                </div>

                <div class="space-y-2">
                    @forelse($lowStockProducts as $lp)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-900/60 text-xs font-mono">
                            <div class="flex items-center space-x-2 truncate">
                                <img src="{{ $lp->thumbnail ?: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=80' }}" class="w-8 h-8 rounded-lg object-cover">
                                <span class="text-white truncate max-w-[140px] font-sans">{{ $lp->name }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-pink-500/20 text-pink-400 font-bold">
                                {{ $lp->stock_quantity }} {{ \App\Helpers\LocalizationHelper::get('only_left') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-2 text-center">{{ \App\Helpers\LocalizationHelper::get('stock_sufficient') }}</p>
                    @endforelse
                </div>
            </div>

            <!-- Instant Abandoned Cart 1-Click WhatsApp Booster -->
            <div class="admin-glass rounded-3xl p-5 border border-emerald-500/30 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                    <div class="flex items-center space-x-2 text-emerald-400">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <h4 class="font-cyber font-bold text-xs uppercase">{{ \App\Helpers\LocalizationHelper::get('whatsapp_booster') }}</h4>
                    </div>
                    <a href="{{ route('admin.abandoned_carts.index') }}" class="text-[10px] text-emerald-400 font-mono hover:underline">{{ \App\Helpers\LocalizationHelper::get('view_all') }}</a>
                </div>

                <div class="space-y-2">
                    @forelse($recentAbandoned as $ab)
                        @php
                            $phoneClean = preg_replace('/[^0-9]/', '', $ab->customer_phone);
                            $phoneWithCode = (strlen($phoneClean) === 11 && str_starts_with($phoneClean, '01')) ? '88' . $phoneClean : $phoneClean;
                            $waMessage = rawurlencode("👋 Hello " . ($ab->customer_name ?: 'Shopper') . "! Special 10% voucher for your cart at NEXUS DOKAN: CYBER10");
                        @endphp
                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-900/60 text-xs font-mono">
                            <div>
                                <span class="font-bold text-white block">{{ $ab->customer_name ?: 'Shopper' }}</span>
                                <span class="text-[10px] text-cyan-300">{{ \App\Helpers\BanglaHelper::formatTaka($ab->subtotal) }}</span>
                            </div>
                            @if($ab->customer_phone)
                                <a href="https://wa.me/{{ $phoneWithCode }}?text={{ $waMessage }}" target="_blank" class="px-2.5 py-1 rounded bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 text-[10px] font-bold inline-flex items-center space-x-1">
                                    <i data-lucide="send" class="w-3 h-3"></i>
                                    <span>WhatsApp 💬</span>
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-2 text-center">{{ \App\Helpers\LocalizationHelper::get('no_pending_carts') }}</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- 4. BOTTOM ROW: Recent Orders & Top Selling Products -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Recent Orders (7 Cols) -->
        <div class="lg:col-span-7 admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('recent_orders') }}</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-cyan-400 font-mono hover:underline">{{ \App\Helpers\LocalizationHelper::get('view_all') }} →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-mono">
                    <thead>
                        <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                            <th class="pb-2">{{ \App\Helpers\LocalizationHelper::get('order_no') }}</th>
                            <th class="pb-2">{{ \App\Helpers\LocalizationHelper::get('customer') }}</th>
                            <th class="pb-2">{{ \App\Helpers\LocalizationHelper::get('amount') }}</th>
                            <th class="pb-2">{{ \App\Helpers\LocalizationHelper::get('status') }}</th>
                            <th class="pb-2 text-right">{{ \App\Helpers\LocalizationHelper::get('courier') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($recentOrders as $ord)
                            <tr>
                                <td class="py-2.5 font-bold text-cyan-400">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}">#{{ $ord->order_number }}</a>
                                </td>
                                <td class="py-2.5 text-white">{{ $ord->customer_name }}</td>
                                <td class="py-2.5 font-bold text-emerald-400">{{ \App\Helpers\BanglaHelper::formatTaka($ord->total_amount) }}</td>
                                <td class="py-2.5">
                                    @php
                                        $statusKey = 'status_' . strtolower($ord->payment_status === 'paid' ? 'paid' : $ord->order_status);
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ord->payment_status === 'paid' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-pink-500/20 text-pink-300' }}">
                                        {{ \App\Helpers\LocalizationHelper::get($statusKey, $ord->order_status) }}
                                    </span>
                                </td>
                                <td class="py-2.5 text-right">
                                    @if($ord->courier_consignment_id)
                                        <span class="text-emerald-400 font-bold text-[10px]">✓ {{ \App\Helpers\LocalizationHelper::get('booked') }}</span>
                                    @else
                                        <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-2 py-0.5 rounded bg-cyan-500/10 text-cyan-300 text-[10px] font-bold">
                                            {{ \App\Helpers\LocalizationHelper::get('send_courier') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Selling Products (5 Cols) -->
        <div class="lg:col-span-5 admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('best_sellers') }}</h3>
                <span class="text-[10px] text-slate-500 font-mono">{{ \App\Helpers\LocalizationHelper::get('performance') }}</span>
            </div>

            <div class="space-y-3">
                @foreach($topProducts as $tp)
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-900/60 text-xs font-mono">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $tp->thumbnail ?: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=80' }}" class="w-9 h-9 rounded-xl object-cover">
                            <div>
                                <span class="font-sans font-bold text-white block truncate max-w-[160px]">{{ $tp->name }}</span>
                                <span class="text-[10px] text-cyan-400">{{ \App\Helpers\BanglaHelper::formatTaka($tp->price) }}</span>
                            </div>
                        </div>
                        <span class="font-bold text-emerald-400 text-xs">
                            {{ $tp->sales_count ?: 45 }} {{ \App\Helpers\LocalizationHelper::get('units_sold') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('revenueProfitChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($days) !!},
                datasets: [
                    {
                        label: '{{ \App\Helpers\LocalizationHelper::get('revenue') }} (৳)',
                        data: {!! json_encode($salesData) !!},
                        borderColor: '#00f2fe',
                        backgroundColor: 'rgba(0, 242, 254, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#00f2fe',
                        pointRadius: 4,
                    },
                    {
                        label: '{{ \App\Helpers\LocalizationHelper::get('net_profit') }} (৳)',
                        data: {!! json_encode($profitData) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 3,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { family: 'JetBrains Mono' } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { family: 'JetBrains Mono' } }
                    }
                }
            }
        });
    });
</script>
@endpush
