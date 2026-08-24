@extends('layouts.admin')

@section('page-title', 'সাপ্লায়ার খতিয়ান ও ইনভেন্টরি লেজার (Supplier Ledger)')

@section('content')
<div class="space-y-8" x-data="{ showModal: false }">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL SUPPLIERS</span>
            <p class="font-cyber font-bold text-2xl text-white">{{ $suppliers->total() }} কোম্পানি</p>
            <p class="text-[11px] text-slate-400 font-mono">অ্যাক্টিভ মাল সরবরাহকারী</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL GOODS PURCHASED</span>
            <p class="font-mono font-black text-2xl text-emerald-300">{{ \App\Helpers\BanglaHelper::formatTaka($totalPurchased) }}</p>
            <p class="text-[11px] text-slate-400 font-mono">সর্বমোট ক্রয়কৃত মালের মূল্য</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-pink-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL DUE PAYABLE (বকেয়া)</span>
            <p class="font-mono font-black text-2xl text-pink-400">{{ \App\Helpers\BanglaHelper::formatTaka($totalPayableDue) }}</p>
            <p class="text-[11px] text-slate-400 font-mono">সাপ্লায়ারদের মোট পরিশোধযোগ্য বকেয়া</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.suppliers.index') }}" method="GET" class="flex items-center space-x-3 w-full sm:w-auto font-mono text-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="সাপ্লায়ারের নাম, কোম্পানি, ফোন..." 
                   class="bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400 w-64">
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold">
                Search
            </button>
        </form>

        <button @click="showModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>নতুন সাপ্লায়ার যোগ করুন 📦</span>
        </button>
    </div>

    <!-- Suppliers Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">সাপ্লায়ার / কোম্পানি</th>
                        <th class="pb-3">যোগাযোগ</th>
                        <th class="pb-3">ঠিকানা</th>
                        <th class="pb-3">মোট ক্রয়</th>
                        <th class="pb-3">পরিশোধ</th>
                        <th class="pb-3">বকেয়া (Due)</th>
                        <th class="pb-3 text-right">খতিয়ান (Ledger)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($suppliers as $sup)
                        <tr>
                            <td class="py-3.5">
                                <a href="{{ route('admin.suppliers.show', $sup->id) }}" class="font-sans font-bold text-white hover:text-cyan-300 text-xs block">
                                    {{ $sup->company_name ?: $sup->name }}
                                </a>
                                <span class="text-[10px] text-slate-400">প্রোপ্রাইটর: {{ $sup->name }}</span>
                            </td>
                            <td class="py-3.5 text-slate-300">
                                <div>{{ $sup->phone ?: '-' }}</div>
                                <span class="text-[10px] text-slate-500">{{ $sup->email }}</span>
                            </td>
                            <td class="py-3.5 text-slate-400 text-[11px] max-w-xs truncate">{{ $sup->address ?: '-' }}</td>
                            <td class="py-3.5 font-bold text-white">{{ \App\Helpers\BanglaHelper::formatTaka($sup->total_purchased) }}</td>
                            <td class="py-3.5 text-emerald-400 font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($sup->total_paid) }}</td>
                            <td class="py-3.5 font-bold {{ $sup->current_due > 0 ? 'text-pink-400' : 'text-slate-500' }}">
                                {{ \App\Helpers\BanglaHelper::formatTaka($sup->current_due) }}
                            </td>
                            <td class="py-3.5 text-right">
                                <a href="{{ route('admin.suppliers.show', $sup->id) }}" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-xs font-bold inline-flex items-center space-x-1">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                    <span>লেজার বিবরণী</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">কোনো সাপ্লায়ার পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $suppliers->links() }}
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showModal = false"></div>

        <div class="relative w-full max-w-lg bg-slate-900 border border-cyan-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl z-10 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-base text-white">নতুন সাপ্লায়ার এন্ট্রি</h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                
                <div class="space-y-1">
                    <label class="text-slate-300">যোগাযোগ ব্যক্তির নাম *</label>
                    <input type="text" name="name" required placeholder="e.g. Khandaker Rafiq" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">প্রতিষ্ঠানের নাম (Company Name)</label>
                    <input type="text" name="company_name" placeholder="e.g. CyberTech Importers BD" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300">মোবাইল নম্বর *</label>
                        <input type="text" name="phone" required placeholder="01711223344" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="text-slate-300">ইমেইল</label>
                        <input type="email" name="email" placeholder="supplier@example.com" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">ঠিকানা (Address / Market / Shop No)</label>
                    <textarea name="address" rows="2" placeholder="e.g. Shop #402, Multiplan Center, Elephant Road, Dhaka" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">পূর্বের বকেয়া (Opening Balance ৳)</label>
                    <input type="number" step="0.01" name="opening_balance" value="0" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    সাপ্লায়ার সেভ করুন 💾
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
