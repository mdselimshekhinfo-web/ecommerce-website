@extends('layouts.admin')

@section('page-title', 'নিট প্রফিট অ্যান্ড লস ও জেলাভিত্তিক সেলস অ্যানালিটিক্স (Profit & Loss / BI)')

@section('content')
<div class="space-y-8">

    <!-- Top Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="font-cyber font-bold text-base text-white">এক্সিকিউটিভ ফিনান্সিয়াল অডিট ও লাভ-ক্ষতি রিপোর্ট</h3>
            <p class="text-xs text-slate-400 font-mono">প্রকৃত সেলস, পণ্যের কেনা দাম (COGS) ও নিট প্রফিট মার্জিন</p>
        </div>

        <a href="{{ route('admin.analytics.export_orders') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span>অ্যাকাউন্টিং এক্সেল এক্সপোর্ট (CSV) 📊</span>
        </a>
    </div>

    <!-- P&L Financial KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Revenue -->
        <div class="admin-glass rounded-3xl p-6 border border-cyan-500/30 space-y-2">
            <span class="text-slate-400 font-mono text-[10px] uppercase">১. মোট বিক্রয় রেভিনিউ (Gross Sales)</span>
            <p class="font-mono font-black text-2xl text-cyan-300">{{ \App\Helpers\BanglaHelper::formatTaka($totalRevenue) }}</p>
            <p class="text-[11px] text-slate-400 font-mono">পেইড অর্ডারের মোট কালেকশন</p>
        </div>

        <!-- COGS -->
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-2">
            <span class="text-slate-400 font-mono text-[10px] uppercase">২. পণ্যের কেনা খরচ (Cost of Goods)</span>
            <p class="font-mono font-black text-2xl text-slate-300">-{{ \App\Helpers\BanglaHelper::formatTaka($totalCogs) }}</p>
            <p class="text-[11px] text-slate-500 font-mono">সাপ্লায়ার থেকে কেনার মোট খরচ</p>
        </div>

        <!-- Discounts & Shipping -->
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-2">
            <span class="text-slate-400 font-mono text-[10px] uppercase">৩. কুপন ডিসকাউন্ট ও ভাউচার</span>
            <p class="font-mono font-black text-2xl text-pink-400">-{{ \App\Helpers\BanglaHelper::formatTaka($totalDiscounts) }}</p>
            <p class="text-[11px] text-slate-500 font-mono">প্রোমোর মোট ছাড়</p>
        </div>

        <!-- Net Profit -->
        <div class="admin-glass rounded-3xl p-6 border border-emerald-500/40 bg-emerald-950/20 space-y-2 relative overflow-hidden shadow-neon-green">
            <span class="text-emerald-400 font-mono text-[10px] uppercase font-bold">৪. প্রকৃত নিট লাভ (NET PROFIT)</span>
            <p class="font-mono font-black text-3xl text-emerald-300">{{ \App\Helpers\BanglaHelper::formatTaka($netProfit) }}</p>
            <p class="text-[11px] text-emerald-400 font-mono font-bold">নিট প্রফিট মার্জিন: {{ $profitMargin }}%</p>
        </div>

    </div>

    <!-- 2 Columns: Top 64 Districts Breakdown & Financial Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- 64 Districts Performance (7 Cols) -->
        <div class="lg:col-span-7 admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-sm text-white">বাংলাদেশ ৬4 জেলাভিত্তিক সেলস পরিসংখ্যান</h3>
                <span class="text-xs px-2.5 py-1 rounded bg-cyan-500/10 text-cyan-300 font-mono">Top Districts</span>
            </div>

            <div class="space-y-4">
                @foreach($districtStats as $stat)
                    @php
                        $percent = $totalRevenue > 0 ? round(($stat->total_sales / $totalRevenue) * 100) : 0;
                    @endphp
                    <div class="space-y-1.5 font-mono text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-white">{{ $stat->delivery_district }} ({{ $stat->count }} Orders)</span>
                            <span class="text-cyan-300 font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($stat->total_sales) }} ({{ $percent }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-slate-900 rounded-full overflow-hidden border border-slate-800">
                            <div class="h-full bg-gradient-to-r from-cyan-400 to-indigo-500 transition-all duration-500"
                                 style="width: {{ min(100, max(5, $percent)) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Financial Summary Equation (5 Cols) -->
        <div class="lg:col-span-5 admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-5 font-mono text-xs">
            <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider pb-3 border-b border-slate-800">
                নিট প্রফিট সমীকরণ (P&L Equation)
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between text-slate-300">
                    <span>(+) মোট বিক্রয় রেভিনিউ:</span>
                    <span class="text-white font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($totalRevenue) }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>(-) পণ্যের কেনা দাম (COGS):</span>
                    <span class="text-slate-300">{{ \App\Helpers\BanglaHelper::formatTaka($totalCogs) }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>(-) কুপন ও ভাউচার ছাড়:</span>
                    <span class="text-slate-300">{{ \App\Helpers\BanglaHelper::formatTaka($totalDiscounts) }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>(-) কুরিয়ার হ্যান্ডলিং খরচ:</span>
                    <span class="text-slate-300">{{ \App\Helpers\BanglaHelper::formatTaka($totalShippingCollected * 0.8) }}</span>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-between text-sm font-bold text-white">
                    <span>প্রকৃত নিট প্রফিট:</span>
                    <span class="text-emerald-400 text-lg font-black">{{ \App\Helpers\BanglaHelper::formatTaka($netProfit) }}</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 text-[11px] text-slate-400 leading-relaxed">
                💡 প্রতিটি প্রোডাক্টের ক্রয়ের দাম হিসাব থেকে বাদ দিয়ে এই প্রফিট মার্জিন স্বয়ংক্রিয়ভাবে ক্যালকুলেট করা হয়েছে।
            </div>
        </div>

    </div>

</div>
@endsection
