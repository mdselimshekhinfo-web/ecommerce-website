@extends('layouts.admin')

@section('page-title', 'পারচেজ অর্ডার ও মাল ক্রয়ের তালিকা (Purchase Orders)')

@section('content')
<div class="space-y-8">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL PURCHASE ORDERS</span>
            <p class="font-cyber font-bold text-2xl text-white">{{ $totalPOs }} টি অর্ডার</p>
            <p class="text-[11px] text-slate-400 font-mono">সর্বমোট ক্রয়াদেশ</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL PO VALUE</span>
            <p class="font-mono font-black text-2xl text-emerald-300">{{ \App\Helpers\BanglaHelper::formatTaka($totalPOAmount) }}</p>
            <p class="text-[11px] text-slate-400 font-mono">মোট ক্রয়াদেশের মূল্য</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-pink-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL DUE (বকেয়া)</span>
            <p class="font-mono font-black text-2xl text-pink-400">{{ \App\Helpers\BanglaHelper::formatTaka($totalDue) }}</p>
            <p class="text-[11px] text-slate-400 font-mono">পারচেজ অর্ডারে বকেয়া</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.purchase_orders.index') }}" method="GET" class="flex items-center space-x-3 w-full sm:w-auto font-mono text-xs">
            <select name="status" class="bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none">
                <option value="">সকল স্ট্যাটাস</option>
                <option value="ordered" {{ request('status') === 'ordered' ? 'selected' : '' }}>অর্ডার করা হয়েছে (Ordered)</option>
                <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>গোডাউনে রিসিভ হয়েছে (Received)</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>ড্রাফট (Draft)</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold">
                Filter
            </button>
        </form>

        <a href="{{ route('admin.purchase_orders.create') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>নতুন পারচেজ অর্ডার তৈরি করুন 📄</span>
        </a>
    </div>

    <!-- Purchase Orders Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">PO নম্বর</th>
                        <th class="pb-3">সাপ্লায়ার</th>
                        <th class="pb-3">তারিখ</th>
                        <th class="pb-3">মোট টাকা</th>
                        <th class="pb-3">পরিশোধ</th>
                        <th class="pb-3">বকেয়া</th>
                        <th class="pb-3">রিসিভ স্ট্যাটাস</th>
                        <th class="pb-3 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($purchaseOrders as $po)
                        <tr>
                            <td class="py-3.5">
                                <a href="{{ route('admin.purchase_orders.show', $po->id) }}" class="font-bold text-cyan-400 hover:underline">
                                    {{ $po->po_number }}
                                </a>
                            </td>
                            <td class="py-3.5 text-white font-sans font-medium">
                                {{ $po->supplier->company_name ?: $po->supplier->name }}
                            </td>
                            <td class="py-3.5 text-slate-400">{{ $po->created_at->format('d M Y') }}</td>
                            <td class="py-3.5 font-bold text-white">{{ \App\Helpers\BanglaHelper::formatTaka($po->total_amount) }}</td>
                            <td class="py-3.5 text-emerald-400 font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($po->paid_amount) }}</td>
                            <td class="py-3.5 font-bold {{ $po->due_amount > 0 ? 'text-pink-400' : 'text-slate-500' }}">
                                {{ \App\Helpers\BanglaHelper::formatTaka($po->due_amount) }}
                            </td>
                            <td class="py-3.5">
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $po->status === 'received' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-amber-500/20 text-amber-300 border border-amber-500/40' }}">
                                    {{ $po->status === 'received' ? '✓ Received (ইনভেন্টরি যুক্ত)' : '⏳ Ordered (পেন্ডিং)' }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <a href="{{ route('admin.purchase_orders.show', $po->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold inline-block">
                                    ইনভয়েস দেখুন
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500">কোনো পারচেজ অর্ডার পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $purchaseOrders->links() }}
        </div>
    </div>

</div>
@endsection
