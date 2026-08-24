@extends('layouts.app')

@section('title', 'Live Order Tracking // NEXUS DOKAN BD')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="text-center max-w-xl mx-auto mb-8 space-y-2">
        <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-cyan-500/10 text-cyan-300 border border-cyan-500/30 text-xs font-mono font-bold">
            <i data-lucide="crosshair" class="w-3.5 h-3.5"></i>
            <span>REAL-TIME LOGISTICS RADAR</span>
        </div>
        <h1 class="font-cyber font-black text-3xl text-white tracking-wide">
            TRACK YOUR PARCEL
        </h1>
        <p class="text-xs text-slate-400">Enter your Order Number or Bangladeshi Mobile Number to track live courier delivery.</p>
    </div>

    <!-- Search Form -->
    <div class="glass-card rounded-2xl p-6 mb-8">
        <form action="{{ route('order.track') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6">
                <input type="text" name="order_number" value="{{ request('order_number') }}" placeholder="Order Number (e.g. NX-2026-9812)" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 font-mono">
            </div>
            <div class="sm:col-span-4">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="Phone (e.g. 01812345678)" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 font-mono">
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="w-full h-full py-3 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan flex items-center justify-center space-x-1.5">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>Track</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Tracking Result Box -->
    @if($searchPerformed)
        @if($order)
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-8 border border-cyan-500/30">
                
                <!-- Top Status Header -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="font-cyber font-bold text-lg text-white">ORDER #{{ $order->order_number }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase
                                {{ $order->order_status === 'delivered' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' }}">
                                {{ $order->order_status }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 font-mono mt-1">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>

                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 font-mono">COURIER TRACKING:</span>
                        <p class="font-mono font-bold text-sm text-cyan-300">{{ $order->tracking_code ?: 'TRK-PENDING' }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $order->courier_name }}</p>
                    </div>
                </div>

                <!-- Visual Step-by-Step Courier Timeline -->
                @php
                    $statuses = [
                        'pending' => ['label' => 'Order Placed', 'icon' => 'shopping-bag', 'desc' => 'Order submitted to system'],
                        'processing' => ['label' => 'Processing', 'icon' => 'cpu', 'desc' => 'Verified at Central Hub'],
                        'packed' => ['label' => 'Packed', 'icon' => 'package-check', 'desc' => 'Packed with protective cyber shield'],
                        'shipped' => ['label' => 'In Transit', 'icon' => 'truck', 'desc' => 'Handed to courier partner'],
                        'delivered' => ['label' => 'Delivered', 'icon' => 'home', 'desc' => 'Delivered to customer'],
                    ];

                    $statusKeys = array_keys($statuses);
                    $currentIndex = array_search($order->order_status, $statusKeys);
                    if ($currentIndex === false) $currentIndex = 0;
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 relative">
                    @foreach($statuses as $key => $info)
                        @php
                            $stepIndex = array_search($key, $statusKeys);
                            $isPassed = $stepIndex <= $currentIndex;
                            $isCurrent = $stepIndex === $currentIndex;
                        @endphp
                        <div class="flex sm:flex-col items-center sm:text-center space-x-3 sm:space-x-0 space-y-0 sm:space-y-2 relative group">
                            
                            <!-- Step Dot / Icon -->
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all shrink-0
                                {{ $isPassed ? 'bg-cyan-500/20 border-cyan-400 text-cyan-300 shadow-neon-cyan' : 'bg-slate-900 border-slate-700 text-slate-600' }}
                                {{ $isCurrent ? 'animate-pulse' : '' }}">
                                <i data-lucide="{{ $info['icon'] }}" class="w-5 h-5"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h5 class="text-xs font-bold font-cyber {{ $isPassed ? 'text-white' : 'text-slate-500' }}">
                                    {{ $info['label'] }}
                                </h5>
                                <p class="text-[10px] text-slate-400 hidden sm:block mt-0.5">{{ $info['desc'] }}</p>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Parcel Summary Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-800 text-xs">
                    
                    <!-- Delivery Address Details -->
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                        <h4 class="font-cyber font-bold text-cyan-400 uppercase tracking-wider text-[11px]">Delivery Recipient</h4>
                        <p class="font-bold text-white text-sm">{{ $order->customer_name }}</p>
                        <p class="text-slate-300 font-mono">{{ $order->customer_phone }}</p>
                        <p class="text-slate-400 leading-relaxed">{{ $order->delivery_address }}, {{ $order->delivery_district }}</p>
                    </div>

                    <!-- Items & Payment Summary -->
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                        <h4 class="font-cyber font-bold text-pink-400 uppercase tracking-wider text-[11px]">Order Items</h4>
                        <div class="space-y-1.5 font-mono">
                            @foreach($order->items as $it)
                                <div class="flex items-center justify-between text-slate-300">
                                    <span class="truncate max-w-[200px]">{{ $it->product_name }} x{{ $it->quantity }}</span>
                                    <span class="text-white font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($it->total) }}</span>
                                </div>
                            @endforeach
                            <div class="pt-2 border-t border-slate-800 flex items-center justify-between font-bold text-sm text-cyan-300">
                                <span>Total (with Shipping):</span>
                                <span>{{ \App\Helpers\BanglaHelper::formatTaka($order->total_amount) }}</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        @else
            <div class="glass-card rounded-3xl p-12 text-center space-y-3">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-red-950/60 border border-red-500/30 flex items-center justify-center">
                    <i data-lucide="alert-circle" class="w-7 h-7 text-red-400"></i>
                </div>
                <h3 class="font-cyber font-bold text-base text-white">No Order Found</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Please verify the Order Number (e.g. NX-2026-9812) or the Bangladeshi phone number you used during checkout.</p>
            </div>
        @endif
    @endif

</div>
@endsection
