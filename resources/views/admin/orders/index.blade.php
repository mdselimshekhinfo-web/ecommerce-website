@extends('layouts.admin')

@section('page-title', 'Customer Orders & Logistics Fulfillment')

@section('content')
<div class="space-y-6">

    <!-- Filter & Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-3 font-mono text-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, Phone, TrxID..." 
                   class="bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400 w-56">

            <select name="status" class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="packed" {{ request('status') == 'packed' ? 'selected' : '' }}>Packed</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold transition-all">
                ফিল্টার করুন
            </button>
        </form>

        <a href="{{ route('admin.orders.create') }}" 
           class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 via-indigo-600 to-pink-500 hover:scale-[1.02] text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-neon-cyan transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>+ ম্যানুয়াল অর্ডার এন্ট্রি (FB / WhatsApp / POS)</span>
        </a>
    </div>

    <!-- Orders Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">Order Number</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">District</th>
                        <th class="pb-3">Payment</th>
                        <th class="pb-3">Total Amount</th>
                        <th class="pb-3">Order Status</th>
                        <th class="pb-3">Courier</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach($orders as $ord)
                        <tr>
                            <td class="py-3.5">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="font-bold text-cyan-400 hover:underline">
                                    #{{ $ord->order_number }}
                                </a>
                                <p class="text-[10px] text-slate-500">{{ $ord->created_at->format('d M, h:i A') }}</p>
                            </td>
                            <td class="py-3.5">
                                <span class="font-sans font-bold text-white block">{{ $ord->customer_name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $ord->customer_phone }}</span>
                            </td>
                            <td class="py-3.5 text-slate-300">{{ $ord->delivery_district }}</td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $ord->payment_status === 'paid' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                                    {{ $ord->payment_method }} ({{ $ord->payment_status }})
                                </span>
                            </td>
                            <td class="py-3.5 font-bold text-white text-sm">
                                {{ \App\Helpers\BanglaHelper::formatTaka($ord->total_amount) }}
                            </td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $ord->order_status === 'delivered' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-cyan-500/20 text-cyan-300' }}">
                                    {{ $ord->order_status }}
                                </span>
                            </td>
                            <td class="py-3.5 text-slate-400 text-[11px]">
                                {{ $ord->courier_name }}
                            </td>
                            <td class="py-3.5 text-right space-x-2">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-xs font-bold inline-block">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
