@extends('layouts.app')

@section('title', 'Cyber Checkout // Fast & Secure BD Order - NEXUS DOKAN')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="checkoutPage()">

    <!-- Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-xs font-mono text-slate-400 mb-2">
            <a href="{{ route('home') }}" class="hover:text-cyan-400">HOME</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-cyan-400">CART</a>
            <span>/</span>
            <span class="text-cyan-400 font-semibold">CHECKOUT</span>
        </div>
        <h1 class="font-cyber font-black text-3xl text-white tracking-wide">
            CYBER CHECKOUT
        </h1>
        <p class="text-xs text-slate-400 mt-1">Provide your delivery address in Bangladesh and select payment method.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Shipping & Payment Details (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Section 1: Customer & BD Address -->
                <div class="glass-card rounded-2xl p-6 space-y-5">
                    <div class="flex items-center space-x-3 pb-3 border-b border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center">
                            <span class="font-cyber font-bold text-xs text-cyan-400">01</span>
                        </div>
                        <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                            Shipping Details (Bangladesh)
                        </h3>
                    </div>

                    @if(Auth::check() && Auth::user()->addresses->count() > 0)
                        <div class="p-3 rounded-2xl bg-cyan-950/30 border border-cyan-500/30 space-y-2">
                            <span class="text-[10px] text-cyan-300 font-mono font-bold uppercase block">📍 সংরক্ষিত ঠিকানা থেকে সিলেক্ট করুন (১-ক্লিক):</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach(Auth::user()->addresses as $sAddr)
                                    <button type="button" 
                                            @click="
                                                document.querySelector('input[name=customer_name]').value = '{{ $sAddr->name }}';
                                                document.querySelector('input[name=customer_phone]').value = '{{ $sAddr->phone }}';
                                                selectedDistrictKey = '{{ $sAddr->district }}';
                                                updateDistrict();
                                                document.querySelector('textarea[name=delivery_address]').value = '{{ $sAddr->address }}';
                                            "
                                            class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-cyan-500/20 border border-cyan-500/40 text-cyan-300 font-mono text-xs flex items-center space-x-1.5 transition-all">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                        <span>{{ $sAddr->label }}: {{ $sAddr->district }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Full Name -->
                        <div class="space-y-1.5">
                            <label class="font-mono text-xs text-slate-300">Full Name *</label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name', Auth::user()?->name) }}" placeholder="e.g. Tanvir Ahmed" 
                                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400">
                        </div>

                        <!-- BD Phone Number -->
                        <div class="space-y-1.5">
                            <label class="font-mono text-xs text-slate-300">Phone Number (01XXXXXXXXX) *</label>
                            <input type="text" name="customer_phone" required value="{{ old('customer_phone', Auth::user()?->phone) }}" placeholder="01711000111" 
                                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400">
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="font-mono text-xs text-slate-300">Email Address (Optional)</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', Auth::user()?->email) }}" placeholder="tanvir@gmail.com" 
                                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400">
                        </div>

                        <!-- District Dropdown -->
                        <div class="space-y-1.5">
                            <label class="font-mono text-xs text-slate-300">District / জেলা *</label>
                            <select name="delivery_district" x-model="selectedDistrictKey" @change="updateDistrict()" required
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                                @foreach($districts as $key => $d)
                                    <option value="{{ $key }}" {{ (old('delivery_district', Auth::user()?->district) == $key) ? 'selected' : '' }}>
                                        {{ $key }} ({{ $d['name_bn'] }}) - Delivery ৳{{ $d['cost'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- Full Street Address -->
                    <div class="space-y-1.5">
                        <label class="font-mono text-xs text-slate-300">Full Delivery Address (House/Road/Area/Thana) *</label>
                        <textarea name="delivery_address" required rows="2" placeholder="e.g. House #14, Road #05, Block C, Banani, Dhaka" 
                                  class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400">{{ old('delivery_address', Auth::user()?->address) }}</textarea>
                    </div>

                    <!-- Delivery Notes -->
                    <div class="space-y-1.5">
                        <label class="font-mono text-xs text-slate-300">Special Delivery Instructions (Optional)</label>
                        <input type="text" name="delivery_notes" placeholder="e.g. Please call before reaching my house" 
                               class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400">
                    </div>

                </div>

                <!-- Section 2: Payment Method Selector -->
                <div class="glass-card rounded-2xl p-6 space-y-5">
                    <div class="flex items-center space-x-3 pb-3 border-b border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-pink-500/20 border border-pink-500/40 flex items-center justify-center">
                            <span class="font-cyber font-bold text-xs text-pink-400">02</span>
                        </div>
                        <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                            Select Payment Method
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        
                        <!-- bKash -->
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="bkash" x-model="paymentMethod" class="peer sr-only">
                            <div class="p-4 rounded-xl bg-slate-900 border border-slate-700 peer-checked:border-[#e2136e] peer-checked:bg-[#e2136e]/10 text-center space-y-2 transition-all hover:border-slate-500">
                                <span class="px-2.5 py-1 rounded bg-[#e2136e]/20 border border-[#e2136e]/40 text-[#ff4b98] font-bold text-xs font-mono block">bKash Direct</span>
                                <p class="text-[10px] text-slate-400">Instant Online Gateway</p>
                            </div>
                        </label>

                        <!-- Nagad -->
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="nagad" x-model="paymentMethod" class="peer sr-only">
                            <div class="p-4 rounded-xl bg-slate-900 border border-slate-700 peer-checked:border-[#f7941d] peer-checked:bg-[#f7941d]/10 text-center space-y-2 transition-all hover:border-slate-500">
                                <span class="px-2.5 py-1 rounded bg-[#f7941d]/20 border border-[#f7941d]/40 text-[#ffa940] font-bold text-xs font-mono block">Nagad</span>
                                <p class="text-[10px] text-slate-400">Instant Online Gateway</p>
                            </div>
                        </label>

                        <!-- Cash on Delivery -->
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="peer sr-only">
                            <div class="p-4 rounded-xl bg-slate-900 border border-slate-700 peer-checked:border-emerald-400 peer-checked:bg-emerald-500/10 text-center space-y-2 transition-all hover:border-slate-500">
                                <span class="px-2.5 py-1 rounded bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 font-bold text-xs font-mono block">Cash on Delivery</span>
                                <p class="text-[10px] text-slate-400">Pay when you receive</p>
                            </div>
                        </label>

                    </div>

                    <!-- Payment Sub-Panel Instructions -->
                    <div x-show="paymentMethod === 'bkash'" class="p-4 rounded-xl bg-[#e2136e]/10 border border-[#e2136e]/30 space-y-3">
                        <div class="flex items-center space-x-2 text-xs font-bold text-[#ff4b98]">
                            <i data-lucide="smartphone" class="w-4 h-4"></i>
                            <span>bKash Direct Gateway Mode</span>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Upon clicking "Place Cyber Order", your simulated bKash secure payment modal will authorize instantly or you can provide a TrxID below.
                        </p>
                        <div class="space-y-1">
                            <label class="text-[11px] font-mono text-slate-400">bKash Transaction ID (Optional for Demo):</label>
                            <input type="text" name="bkash_trx_id" placeholder="e.g. BKS9X84N72A" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white">
                        </div>
                    </div>

                    <div x-show="paymentMethod === 'nagad'" x-cloak class="p-4 rounded-xl bg-[#f7941d]/10 border border-[#f7941d]/30 space-y-3">
                        <div class="flex items-center space-x-2 text-xs font-bold text-[#ffa940]">
                            <i data-lucide="smartphone" class="w-4 h-4"></i>
                            <span>Nagad Gateway Mode</span>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Fast Nagad checkout. Transaction will be verified automatically.
                        </p>
                        <div class="space-y-1">
                            <label class="text-[11px] font-mono text-slate-400">Nagad Transaction ID (Optional):</label>
                            <input type="text" name="nagad_trx_id" placeholder="e.g. NGD8942LK1" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white">
                        </div>
                    </div>

                    <div x-show="paymentMethod === 'cod'" x-cloak class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-xs text-slate-300 space-y-1">
                        <p class="font-bold text-emerald-400 flex items-center">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-1.5"></i> Cash on Delivery Selected
                        </p>
                        <p>You can pay the full bill to the courier rider via Cash, bKash or Nagad when your parcel arrives at your doorstep.</p>
                    </div>

                </div>

            </div>

            <!-- Right: Order Summary Breakdown (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <div class="glass-card rounded-2xl p-6 space-y-5 sticky top-24 border border-cyan-500/20">
                    <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                        Order Breakdown
                    </h3>

                    <!-- Mini items list -->
                    <div class="divide-y divide-slate-800/80 max-h-56 overflow-y-auto pr-1">
                        @foreach($cart as $item)
                            <div class="py-2.5 flex items-center justify-between text-xs">
                                <div class="flex items-center space-x-2.5 min-w-0 pr-2">
                                    <img src="{{ $item['thumbnail'] }}" class="w-10 h-10 object-cover rounded-lg border border-slate-700 shrink-0">
                                    <div class="truncate">
                                        <h5 class="font-semibold text-white truncate">{{ $item['name'] }}</h5>
                                        <p class="text-[10px] text-slate-400 font-mono">Qty: {{ $item['quantity'] }} • {{ $item['variant'] }}</p>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-white shrink-0">{{ \App\Helpers\BanglaHelper::formatTaka($item['total']) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Price Calculations -->
                    <div class="pt-3 border-t border-slate-800 space-y-2 text-xs font-mono">
                        <div class="flex items-center justify-between text-slate-400">
                            <span>Subtotal:</span>
                            <span class="text-white font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($subtotal) }}</span>
                        </div>

                        @if($discount > 0)
                            <div class="flex items-center justify-between text-emerald-400">
                                <span>Voucher ({{ $coupon['code'] }}):</span>
                                <span>-{{ \App\Helpers\BanglaHelper::formatTaka($discount) }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-slate-400">
                            <span>Delivery Fee (<span x-text="selectedDistrictKey"></span>):</span>
                            <span class="text-cyan-300 font-bold" x-text="'৳' + shippingCost">৳{{ $defaultShipping }}</span>
                        </div>
                    </div>

                    <!-- Grand Total -->
                    <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="font-cyber font-bold text-sm text-white">Grand Total:</span>
                        <span class="font-mono font-black text-2xl text-cyan-300" x-text="'৳' + ({{ max(0, $subtotal - $discount) }} + shippingCost).toLocaleString()"></span>
                    </div>

                    <!-- Place Order CTA -->
                    <button type="submit" class="w-full py-4 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan flex items-center justify-center space-x-2">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        <span>Confirm & Place Order ➔</span>
                    </button>

                    <p class="text-[10px] text-slate-500 text-center font-mono">
                        🔒 256-Bit Encrypted Secure Checkout
                    </p>
                </div>

            </div>

        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    function checkoutPage() {
        return {
            districts: @json($districts),
            selectedDistrictKey: '{{ old("delivery_district", Auth::user()?->district ?? "Dhaka") }}',
            shippingCost: {{ $defaultShipping }},
            paymentMethod: 'bkash',

            init() {
                this.updateDistrict();
            },

            updateDistrict() {
                const d = this.districts[this.selectedDistrictKey];
                if (d) {
                    this.shippingCost = d.cost;
                }
            }
        }
    }
</script>
@endpush
