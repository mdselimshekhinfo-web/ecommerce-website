@extends('layouts.admin')

@section('page-title', 'সাপ্লায়ার খতিয়ান ও হিসাব বিবরণী: ' . $supplier->company_name)

@section('content')
<div class="space-y-8" x-data="{ showPayModal: false }">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.suppliers.index') }}" class="text-xs text-slate-400 font-mono hover:text-white flex items-center space-x-1">
            <i data-lucide="arrow-left" class="w-4 h-4 text-cyan-400"></i>
            <span>সকল সাপ্লায়ারের তালিকা</span>
        </a>

        <div class="flex items-center space-x-3">
            <button @click="showPayModal = true" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all flex items-center space-x-1.5">
                <i data-lucide="banknote" class="w-4 h-4"></i>
                <span>পেমেন্ট এন্ট্রি করুন 💵</span>
            </button>
            <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-cyan-400 text-cyan-300 text-xs font-mono font-bold transition-all">
                🖨️ প্রিন্ট লেজার
            </button>
        </div>
    </div>

    <!-- Supplier Profile & Balance Summary Card -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <!-- Details (7 Cols) -->
        <div class="md:col-span-7 space-y-3">
            <div class="flex items-center space-x-2">
                <h2 class="font-cyber font-bold text-xl text-white">{{ $supplier->company_name ?: $supplier->name }}</h2>
                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                    {{ $supplier->status }}
                </span>
            </div>
            <p class="text-xs text-slate-300">প্রোপ্রাইটর: <b>{{ $supplier->name }}</b></p>
            <p class="text-xs text-slate-400 font-mono">ফোন: {{ $supplier->phone }} | ইমেইল: {{ $supplier->email ?: '-' }}</p>
            <p class="text-xs text-slate-400">ঠিকানা: {{ $supplier->address ?: 'না' }}</p>
        </div>

        <!-- Ledger Financial Balances (5 Cols) -->
        <div class="md:col-span-5 grid grid-cols-2 gap-3 text-xs font-mono">
            <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 text-center">
                <span class="text-slate-500 text-[10px] uppercase">TOTAL PURCHASED</span>
                <p class="font-bold text-white text-sm mt-0.5">{{ \App\Helpers\BanglaHelper::formatTaka($supplier->total_purchased) }}</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 text-center">
                <span class="text-slate-500 text-[10px] uppercase">TOTAL PAID</span>
                <p class="font-bold text-emerald-400 text-sm mt-0.5">{{ \App\Helpers\BanglaHelper::formatTaka($supplier->total_paid) }}</p>
            </div>
            <div class="col-span-2 p-3.5 rounded-xl bg-pink-950/30 border border-pink-500/30 text-center">
                <span class="text-pink-400 text-[10px] uppercase font-bold">CURRENT DUE BALANCE (বর্তমান বকেয়া)</span>
                <p class="font-black text-xl text-pink-300 mt-0.5">{{ \App\Helpers\BanglaHelper::formatTaka($supplier->current_due) }}</p>
            </div>
        </div>

    </div>

    <!-- Ledger Transaction Statement Table -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-4">
        <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider pb-3 border-b border-slate-800">
            লেজার খতিয়ান বিবরণী (Statement History)
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">তারিখ</th>
                        <th class="pb-3">বিবরণ / ট্রানজেকশন টাইপ</th>
                        <th class="pb-3">পেমেন্ট মেথড / রেফারেন্স</th>
                        <th class="pb-3 text-right">মাল ক্রয় (Debit +)</th>
                        <th class="pb-3 text-right">পরিশোধ (Credit -)</th>
                        <th class="pb-3 text-right">ব্যালেন্স (Due)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($supplier->transactions as $trx)
                        <tr>
                            <td class="py-3.5 text-slate-400">{{ $trx->created_at->format('d M Y, h:i A') }}</td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $trx->type === 'purchase' ? 'bg-cyan-500/20 text-cyan-300' : 'bg-emerald-500/20 text-emerald-300' }}">
                                    {{ $trx->type === 'purchase' ? 'মাল ক্রয় (Bill)' : 'পেমেন্ট প্রদান' }}
                                </span>
                                <p class="text-[11px] text-slate-400 mt-1">{{ $trx->notes }}</p>
                            </td>
                            <td class="py-3.5 text-slate-300">
                                {{ $trx->payment_method ? strtoupper($trx->payment_method) : '-' }}
                                @if($trx->reference_no)
                                    <p class="text-[10px] text-slate-500">Ref: {{ $trx->reference_no }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 text-right font-bold {{ $trx->type === 'purchase' ? 'text-white' : 'text-slate-600' }}">
                                {{ $trx->type === 'purchase' ? \App\Helpers\BanglaHelper::formatTaka($trx->amount) : '-' }}
                            </td>
                            <td class="py-3.5 text-right font-bold {{ $trx->type === 'payment' ? 'text-emerald-400' : 'text-slate-600' }}">
                                {{ $trx->type === 'payment' ? \App\Helpers\BanglaHelper::formatTaka($trx->amount) : '-' }}
                            </td>
                            <td class="py-3.5 text-right font-bold text-pink-300">
                                {{ \App\Helpers\BanglaHelper::formatTaka($trx->running_balance) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">কোনো লেনদেন পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div x-show="showPayModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showPayModal = false"></div>

        <div class="relative w-full max-w-md bg-slate-900 border border-emerald-500/40 rounded-3xl p-6 sm:p-8 shadow-2xl z-10 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-base text-white">সাপ্লায়ার পেমেন্ট এন্ট্রি</h3>
                <button @click="showPayModal = false" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.suppliers.add_payment', $supplier->id) }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                
                <div class="p-3 rounded-xl bg-pink-950/30 border border-pink-500/30 flex justify-between text-xs">
                    <span class="text-slate-300">বর্তমান মোট বকেয়া:</span>
                    <span class="font-bold text-pink-300">{{ \App\Helpers\BanglaHelper::formatTaka($supplier->current_due) }}</span>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">পরিশোধের পরিমাণ (৳ BDT) *</label>
                    <input type="number" step="0.01" name="amount" required placeholder="e.g. 10000" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-emerald-400 text-sm font-bold">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">পেমেন্ট মেথড *</label>
                    <select name="payment_method" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        <option value="bank">ব্যাংক ট্রান্সফার (Bank Transfer)</option>
                        <option value="bkash">বিকাশ মার্চেন্ট / পার্সোনাল (bKash)</option>
                        <option value="cash">নগদ ক্যাশ (Cash)</option>
                        <option value="nagad">নগদ (Nagad)</option>
                        <option value="cheque">চেক (Cheque)</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">ট্রানজেকশন / চেক নম্বর (Ref No)</label>
                    <input type="text" name="reference_no" placeholder="e.g. EBL-TRX-948123" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">নোট / মন্তব্য</label>
                    <textarea name="notes" rows="2" placeholder="e.g. Partial payment for PO-2026-0001" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    পেমেন্ট সম্পন্ন করুন 💵
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
