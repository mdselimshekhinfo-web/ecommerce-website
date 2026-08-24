@extends('layouts.app')

@section('title', 'Shopping Cart // NEXUS DOKAN BD')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="cartPage()">

    <!-- Breadcrumb & Title -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-xs font-mono text-slate-400 mb-2">
            <a href="{{ route('home') }}" class="hover:text-cyan-400">HOME</a>
            <span>/</span>
            <span class="text-cyan-400 font-semibold">SHOPPING CART</span>
        </div>
        <h1 class="font-cyber font-black text-3xl text-white tracking-wide">
            YOUR CYBER CART
        </h1>
    </div>

    @if(empty($cart))
        <div class="glass-card rounded-3xl p-16 text-center space-y-4">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-slate-900 border border-slate-700 flex items-center justify-center">
                <i data-lucide="shopping-cart" class="w-10 h-10 text-slate-500"></i>
            </div>
            <h3 class="font-cyber font-bold text-xl text-white">Your Cart is Currently Empty</h3>
            <p class="text-xs text-slate-400 max-w-md mx-auto">Explore our collection of mechanical setups, ANC cyber audio, and smart gadgets to start building your futuristic order.</p>
            <a href="{{ route('shop.index') }}" class="inline-block px-8 py-3.5 rounded-xl cyber-btn text-xs font-cyber font-bold uppercase tracking-wider shadow-neon-cyan">
                Start Shopping Now
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Items Table (8 Cols) -->
            <div class="lg:col-span-8 space-y-4">
                
                <div class="glass-card rounded-2xl p-4 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                        <h3 class="font-cyber font-bold text-sm text-white">Cart Items ({{ count($cart) }})</h3>
                        <a href="{{ route('cart.clear') }}" class="text-xs text-red-400 hover:text-red-300 font-mono flex items-center space-x-1">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            <span>Clear Entire Cart</span>
                        </a>
                    </div>

                    <div class="divide-y divide-slate-800/80">
                        @foreach($cart as $key => $item)
                            <div class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $item['thumbnail'] }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-xl border border-slate-700">
                                    <div>
                                        <a href="{{ route('product.show', $item['slug']) }}" class="font-semibold text-xs sm:text-sm text-white hover:text-cyan-400 transition-colors">
                                            {{ $item['name'] }}
                                        </a>
                                        <p class="text-[11px] font-mono text-cyan-400 mt-0.5">{{ $item['variant'] }}</p>
                                        <p class="text-xs font-mono font-bold text-slate-300 mt-1 sm:hidden">
                                            {{ \App\Helpers\BanglaHelper::formatTaka($item['price']) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between w-full sm:w-auto sm:space-x-8">
                                    <!-- Price -->
                                    <div class="hidden sm:block text-right">
                                        <span class="font-mono text-xs text-slate-400">Unit Price</span>
                                        <p class="font-mono font-bold text-sm text-white">{{ \App\Helpers\BanglaHelper::formatTaka($item['price']) }}</p>
                                    </div>

                                    <!-- Qty -->
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center space-x-1.5 bg-slate-900 border border-slate-700 rounded-xl px-2 py-1">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="text-slate-400 hover:text-white px-1 font-mono text-xs">-</button>
                                        <span class="text-xs font-mono font-bold text-white px-2">{{ $item['quantity'] }}</span>
                                        <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="text-slate-400 hover:text-white px-1 font-mono text-xs">+</button>
                                    </form>

                                    <!-- Total -->
                                    <div class="text-right">
                                        <span class="hidden sm:block font-mono text-xs text-slate-400">Item Total</span>
                                        <p class="font-mono font-black text-sm text-cyan-300">{{ \App\Helpers\BanglaHelper::formatTaka($item['total']) }}</p>
                                    </div>

                                    <!-- Delete -->
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <button type="submit" class="p-2 text-slate-500 hover:text-red-400 rounded-lg hover:bg-red-500/10 transition-colors">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Continue Shopping Button -->
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center space-x-2 text-xs font-mono text-cyan-400 hover:text-cyan-300">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Continue Cyber Shopping</span>
                    </a>
                </div>

            </div>

            <!-- Right: Summary Card (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Coupon Box -->
                <div class="glass-card rounded-2xl p-5 space-y-3">
                    <h4 class="font-cyber font-bold text-xs text-white uppercase tracking-wider">Apply Promo / Cyber Voucher</h4>
                    
                    @if($coupon)
                        <div class="p-3 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-mono font-bold text-cyan-300">{{ $coupon['code'] }}</span>
                                <p class="text-[10px] text-slate-400">{{ $coupon['description'] }}</p>
                            </div>
                            <form action="{{ route('cart.remove_coupon') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-400 hover:text-white text-xs font-mono">Remove</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.apply_coupon') }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="code" placeholder="e.g. CYBER10, NEXUS200" class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white uppercase focus:outline-none focus:border-cyan-400">
                            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-xs font-bold font-mono">
                                Apply
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Order Summary Breakdown -->
                <div class="glass-card rounded-2xl p-6 space-y-4 border border-cyan-500/20">
                    <h4 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">Order Summary</h4>
                    
                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-center justify-between text-slate-300">
                            <span>Subtotal:</span>
                            <span class="font-mono font-bold text-white">{{ \App\Helpers\BanglaHelper::formatTaka($subtotal) }}</span>
                        </div>

                        @if($discount > 0)
                            <div class="flex items-center justify-between text-emerald-400">
                                <span>Voucher Discount:</span>
                                <span class="font-mono font-bold">-{{ \App\Helpers\BanglaHelper::formatTaka($discount) }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-slate-300">
                            <span>Estimated BD Shipping:</span>
                            <span class="font-mono text-cyan-400">৳60 - ৳120</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="font-cyber font-bold text-sm text-white">Estimated Total:</span>
                        <span class="font-mono font-black text-2xl text-cyan-300">{{ \App\Helpers\BanglaHelper::formatTaka($total) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="block w-full py-4 rounded-xl cyber-btn text-center font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan">
                        Proceed to Checkout ➔
                    </a>

                    <div class="flex items-center justify-center space-x-2 text-[10px] font-mono text-slate-400 pt-2">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span>Guaranteed Safe & Secure Checkout</span>
                    </div>
                </div>

            </div>

        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function cartPage() {
        return {}
    }
</script>
@endpush
