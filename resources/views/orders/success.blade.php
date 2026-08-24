@extends('layouts.app')

@section('title', 'Order Confirmed #' . $order->order_number . ' // NEXUS DOKAN BD')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">

    <div class="glass-card rounded-3xl p-8 sm:p-12 space-y-6 relative overflow-hidden border border-emerald-500/30">
        
        <!-- Success Icon Burst -->
        <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shadow-neon-green">
            <i data-lucide="check-circle-2" class="w-10 h-10 text-emerald-400"></i>
        </div>

        <div class="space-y-2">
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 text-xs font-mono font-bold">
                <span>ORDER PLACED SUCCESSFULLY</span>
            </div>
            <h1 class="font-cyber font-black text-3xl sm:text-4xl text-white tracking-wide">
                THANK YOU FOR YOUR ORDER!
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-lg mx-auto">
                ধন্যবাদ! আপনার সাইবার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে। We have sent an order confirmation SMS/email to your phone number.
            </p>
        </div>

        <!-- Key Order Information Card -->
        <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 grid grid-cols-2 sm:grid-cols-4 gap-4 text-left font-mono text-xs">
            <div>
                <span class="text-slate-400 text-[10px]">ORDER NUMBER:</span>
                <p class="font-bold text-cyan-300 text-sm mt-0.5">{{ $order->order_number }}</p>
            </div>
            <div>
                <span class="text-slate-400 text-[10px]">TOTAL AMOUNT:</span>
                <p class="font-bold text-white text-sm mt-0.5">{{ \App\Helpers\BanglaHelper::formatTaka($order->total_amount) }}</p>
            </div>
            <div>
                <span class="text-slate-400 text-[10px]">PAYMENT METHOD:</span>
                <p class="font-bold text-pink-400 text-sm mt-0.5 uppercase">{{ $order->payment_method }}</p>
            </div>
            <div>
                <span class="text-slate-400 text-[10px]">COURIER PARTNER:</span>
                <p class="font-bold text-emerald-400 text-sm mt-0.5">{{ $order->courier_name }}</p>
            </div>
        </div>

        <!-- Action Links -->
        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
            <a href="{{ route('order.track', ['order_number' => $order->order_number]) }}" class="px-6 py-3.5 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan flex items-center space-x-2">
                <i data-lucide="package" class="w-4 h-4"></i>
                <span>Track Live Courier ➔</span>
            </a>

            <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="px-6 py-3.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-cyan-400 text-slate-300 hover:text-white font-mono text-xs font-bold flex items-center space-x-2 transition-all">
                <i data-lucide="printer" class="w-4 h-4 text-cyan-400"></i>
                <span>Print Invoice (PDF/HTML)</span>
            </a>

            <a href="{{ route('home') }}" class="px-6 py-3.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-mono">
                Continue Shopping
            </a>
        </div>

    </div>

</div>
@endsection
