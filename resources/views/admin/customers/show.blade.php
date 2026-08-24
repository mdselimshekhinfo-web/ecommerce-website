@extends('layouts.admin')

@section('page-title', 'গ্রাহকের প্রোফাইল ও অর্ডার ইতিহাস: ' . $customer->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.customers.index') }}" class="text-xs text-slate-400 font-mono hover:text-white flex items-center space-x-1">
            <i data-lucide="arrow-left" class="w-4 h-4 text-cyan-400"></i>
            <span>সকল গ্রাহকের তালিকা</span>
        </a>
    </div>

    <!-- Customer Profile Card -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <img src="{{ $customer->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80' }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-cyan-400/40 shadow-neon-cyan">
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <h2 class="font-cyber font-bold text-xl text-white">{{ $customer->name }}</h2>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/40">
                        {{ $customer->orders->count() >= 5 ? '👑 VIP Platinum' : 'VIP Member' }}
                    </span>
                </div>
                <p class="text-xs text-slate-300 font-mono">{{ $customer->email }} • {{ $customer->phone ?: 'No phone' }}</p>
                <p class="text-xs text-slate-400">ঠিকানা: {{ $customer->address ?: '-' }}, {{ $customer->district }}</p>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 text-center font-mono text-xs">
            <span class="text-slate-500 uppercase text-[10px]">TOTAL LIFETIME SPENT</span>
            <p class="font-black text-2xl text-cyan-300 mt-0.5">{{ \App\Helpers\BanglaHelper::formatTaka($totalSpent) }}</p>
            <p class="text-[10px] text-emerald-400 mt-0.5">{{ $customer->orders->count() }} টি সম্পন্নকৃত অর্ডার</p>
        </div>
    </div>

    <!-- Customer Orders History Table -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-4">
        <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider pb-3 border-b border-slate-800">
            পূর্ববর্তী সকল অর্ডারের তালিকা
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">অর্ডার নম্বর</th>
                        <th class="pb-3">তারিখ</th>
                        <th class="pb-3">পেমেন্ট</th>
                        <th class="pb-3">মোট টাকা</th>
                        <th class="pb-3">স্ট্যাটাস</th>
                        <th class="pb-3 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($customer->orders as $ord)
                        <tr>
                            <td class="py-3.5 font-bold text-cyan-400">#{{ $ord->order_number }}</td>
                            <td class="py-3.5 text-slate-400">{{ $ord->created_at->format('d M Y, h:i A') }}</td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ord->payment_status === 'paid' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-pink-500/20 text-pink-300' }}">
                                    {{ $ord->payment_method }} ({{ $ord->payment_status }})
                                </span>
                            </td>
                            <td class="py-3.5 font-bold text-white">{{ \App\Helpers\BanglaHelper::formatTaka($ord->total_amount) }}</td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ord->order_status === 'delivered' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-cyan-500/20 text-cyan-300' }}">
                                    {{ $ord->order_status }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs">
                                    বিস্তারিত
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">কোনো পূর্ববর্তী অর্ডার পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
