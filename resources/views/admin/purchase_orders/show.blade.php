@extends('layouts.admin')

@section('page-title', 'পারচেজ অর্ডার বিবরণী #' . $po->po_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.purchase_orders.index') }}" class="text-xs text-slate-400 font-mono hover:text-white flex items-center space-x-1">
            <i data-lucide="arrow-left" class="w-4 h-4 text-cyan-400"></i>
            <span>সকল পারচেজ অর্ডারের তালিকা</span>
        </a>

        <div class="flex items-center space-x-3">
            @if($po->status !== 'received')
                <form action="{{ route('admin.purchase_orders.receive', $po->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে পণ্যগুলো গোডাউনে রিসিভ হয়েছে? এটি ইনভেন্টরি স্টক বাড়িয়ে দেবে।')">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all flex items-center space-x-1.5">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        <span>গোডাউনে মাল রিসিভ করুন (Update Stock 📦)</span>
                    </button>
                </form>
            @endif

            <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-cyan-400 text-cyan-300 text-xs font-mono font-bold transition-all">
                🖨️ প্রিন্ট অর্ডার
            </button>
        </div>
    </div>

    <!-- PO Details Card -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-6">
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
            <div>
                <span class="text-[10px] font-mono text-cyan-400 font-bold uppercase">PURCHASE INVOICE</span>
                <h2 class="font-cyber font-black text-2xl text-white mt-1">{{ $po->po_number }}</h2>
                <p class="text-xs text-slate-400 font-mono">তারিখ: {{ $po->created_at->format('d M Y, h:i A') }}</p>
            </div>

            <div class="text-left sm:text-right space-y-1 font-mono text-xs">
                <div>স্ট্যাটাস: 
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $po->status === 'received' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                        {{ $po->status }}
                    </span>
                </div>
                <div>পেমেন্ট: 
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $po->payment_status === 'paid' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-pink-500/20 text-pink-300' }}">
                        {{ $po->payment_status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Supplier Details -->
        <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 text-xs font-mono space-y-1">
            <span class="text-slate-500 text-[10px] uppercase">সাপ্লায়ার / সরবরাহকারী:</span>
            <p class="font-bold text-white text-sm font-sans">{{ $po->supplier->company_name ?: $po->supplier->name }}</p>
            <p class="text-slate-300">মোবাইল: {{ $po->supplier->phone }} | ইমেইল: {{ $po->supplier->email ?: '-' }}</p>
            <p class="text-slate-400">ঠিকানা: {{ $po->supplier->address }}</p>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">আইটেম বিবরণ</th>
                        <th class="pb-3 text-right">একক কেনা দাম (Unit Cost)</th>
                        <th class="pb-3 text-center">পরিমাণ (Qty)</th>
                        <th class="pb-3 text-right">মোট টাকা</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach($po->items as $it)
                        <tr>
                            <td class="py-3 font-bold text-white font-sans">{{ $it->product_name }}</td>
                            <td class="py-3 text-right text-slate-300">{{ \App\Helpers\BanglaHelper::formatTaka($it->unit_cost) }}</td>
                            <td class="py-3 text-center text-white font-bold">{{ $it->quantity }} টি</td>
                            <td class="py-3 text-right font-bold text-cyan-300">{{ \App\Helpers\BanglaHelper::formatTaka($it->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="pt-4 border-t border-slate-800 flex justify-end">
            <div class="w-64 space-y-2 text-xs font-mono">
                <div class="flex justify-between text-slate-400">
                    <span>মালের সাব-টোটাল:</span>
                    <span class="text-white font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($po->subtotal) }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>পরিবহন খরচ:</span>
                    <span class="text-slate-300">{{ \App\Helpers\BanglaHelper::formatTaka($po->shipping_cost) }}</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-white pt-2 border-t border-slate-800">
                    <span>সর্বমোট বিল:</span>
                    <span class="text-cyan-400 text-base font-black">{{ \App\Helpers\BanglaHelper::formatTaka($po->total_amount) }}</span>
                </div>
                <div class="flex justify-between text-emerald-400 font-bold">
                    <span>পরিশোধিত:</span>
                    <span>{{ \App\Helpers\BanglaHelper::formatTaka($po->paid_amount) }}</span>
                </div>
                <div class="flex justify-between text-pink-400 font-bold">
                    <span>বকেয়া (Due):</span>
                    <span>{{ \App\Helpers\BanglaHelper::formatTaka($po->due_amount) }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
