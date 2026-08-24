@extends('layouts.admin')

@section('page-title', 'কাস্টমার সিআরএম ও ভিআইপি টায়ারস (Customer CRM & VIP)')

@section('content')
<div class="space-y-8">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL REGISTERED CUSTOMERS</span>
            <p class="font-cyber font-bold text-2xl text-white">{{ $totalCustomers }} জন</p>
            <p class="text-[11px] text-slate-400 font-mono">নিবন্ধিত বাংলাদেশী গ্রাহক</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-amber-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">VIP CYBER CITIZENS</span>
            <p class="font-cyber font-bold text-2xl text-amber-300">VIP PLATINUM</p>
            <p class="text-[11px] text-slate-400 font-mono">নিয়মিত কেনাকাটাকারী ক্রেতা</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">CUSTOMER RETENTION</span>
            <p class="font-cyber font-bold text-2xl text-emerald-400">84.5%</p>
            <p class="text-[11px] text-slate-400 font-mono">রিপিট পারচেজ রেশিও</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex items-center space-x-3 w-full sm:w-auto font-mono text-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="গ্রাহকের নাম, ফোন, ইমেইল..." 
                   class="bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400 w-64">
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold">
                Search
            </button>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">গ্রাহক</th>
                        <th class="pb-3">মোবাইল ও ইমেইল</th>
                        <th class="pb-3">জেলা</th>
                        <th class="pb-3">মোট অর্ডার</th>
                        <th class="pb-3">ভিআইপি টায়ার</th>
                        <th class="pb-3 text-right">প্রোফাইল</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($customers as $c)
                        <tr>
                            <td class="py-3.5 flex items-center space-x-3">
                                <img src="{{ $c->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-9 h-9 rounded-xl object-cover border border-cyan-400/30">
                                <div>
                                    <span class="font-sans font-bold text-white block">{{ $c->name }}</span>
                                    <span class="text-[10px] text-slate-500">Joined: {{ $c->created_at->format('M Y') }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 text-slate-300">
                                <div>{{ $c->phone ?: '-' }}</div>
                                <span class="text-[10px] text-slate-500">{{ $c->email }}</span>
                            </td>
                            <td class="py-3.5 text-slate-300">{{ $c->district ?: 'Dhaka' }}</td>
                            <td class="py-3.5 font-bold text-white">{{ $c->orders_count }} টি অর্ডার</td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $c->orders_count >= 5 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : ($c->orders_count >= 2 ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' : 'bg-slate-800 text-slate-400') }}">
                                    {{ $c->orders_count >= 5 ? '👑 VIP Platinum' : ($c->orders_count >= 2 ? '⭐ VIP Gold' : 'Bronze') }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <a href="{{ route('admin.customers.show', $c->id) }}" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-xs font-bold inline-block">
                                    অর্ডার ইতিহাস
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">কোনো কাস্টমার পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $customers->links() }}
        </div>
    </div>

</div>
@endsection
