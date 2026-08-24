@extends('layouts.app')

@section('title', 'My Cyber Portal // Customer Dashboard - NEXUS DOKAN')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ currentTab: 'orders' }">

    <!-- Header & Profile Overview Banner -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 mb-8 border border-cyan-500/20">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            
            <div class="flex items-center space-x-4">
                <img src="{{ $user->avatar ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80' }}" 
                     class="w-16 h-16 rounded-2xl object-cover border-2 border-cyan-400/40 shadow-neon-cyan">
                <div>
                    <div class="flex items-center space-x-2">
                        <h1 class="font-cyber font-black text-xl sm:text-2xl text-white">{{ $user->name }}</h1>
                        <span class="px-2 py-0.5 rounded bg-cyan-500/20 text-cyan-300 font-mono text-[10px] font-bold uppercase">CYBER CITIZEN</span>
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $user->email }} • {{ $user->phone ?: 'No phone set' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 w-full sm:w-auto font-mono text-xs">
                <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 text-center">
                    <span class="text-slate-400 text-[10px] uppercase">TOTAL SPENT</span>
                    <p class="font-black text-sm text-cyan-300 mt-0.5">{{ \App\Helpers\BanglaHelper::formatTaka($totalSpent) }}</p>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 text-center">
                    <span class="text-slate-400 text-[10px] uppercase">ACTIVE ORDERS</span>
                    <p class="font-black text-sm text-pink-400 mt-0.5">{{ $activeOrdersCount }}</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center space-x-3 mb-6 overflow-x-auto pb-2">
        <button @click="currentTab = 'orders'" :class="currentTab === 'orders' ? 'bg-cyan-500 text-slate-950 font-bold shadow-neon-cyan' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" class="px-5 py-2.5 rounded-xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="package" class="w-4 h-4"></i>
            <span>My Orders ({{ $orders->count() }})</span>
        </button>

        <button @click="currentTab = 'wishlist'" :class="currentTab === 'wishlist' ? 'bg-cyan-500 text-slate-950 font-bold shadow-neon-cyan' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" class="px-5 py-2.5 rounded-xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="heart" class="w-4 h-4"></i>
            <span>Wishlist ({{ $wishlistItems->count() }})</span>
        </button>

        <button @click="currentTab = 'addresses'" :class="currentTab === 'addresses' ? 'bg-cyan-500 text-slate-950 font-bold shadow-neon-cyan' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" class="px-5 py-2.5 rounded-xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="map-pin" class="w-4 h-4"></i>
            <span>Saved Addresses ({{ count($addresses) }})</span>
        </button>

        <button @click="currentTab = 'profile'" :class="currentTab === 'profile' ? 'bg-cyan-500 text-slate-950 font-bold shadow-neon-cyan' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" class="px-5 py-2.5 rounded-xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="settings" class="w-4 h-4"></i>
            <span>Profile Settings</span>
        </button>
    </div>

    <!-- Tab 1: Orders -->
    <div x-show="currentTab === 'orders'" class="space-y-4">
        @forelse($orders as $ord)
            <div class="glass-card rounded-2xl p-5 space-y-4 border border-slate-800 hover:border-cyan-500/30 transition-all">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 pb-3 border-b border-slate-800 text-xs font-mono">
                    <div class="flex items-center space-x-3">
                        <span class="font-bold text-white text-sm">#{{ $ord->order_number }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                            {{ $ord->order_status === 'delivered' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-cyan-500/20 text-cyan-300' }}">
                            {{ $ord->order_status }}
                        </span>
                        <span class="text-slate-500">{{ $ord->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('order.track', ['order_number' => $ord->order_number]) }}" class="text-cyan-400 hover:underline flex items-center space-x-1">
                            <i data-lucide="crosshair" class="w-3.5 h-3.5"></i>
                            <span>Live Track</span>
                        </a>
                        <a href="{{ route('order.invoice', $ord->order_number) }}" target="_blank" class="text-slate-400 hover:text-white flex items-center space-x-1">
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            <span>Invoice</span>
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-slate-800/60">
                    @foreach($ord->items as $it)
                        <div class="py-2.5 flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $it->product_image ?: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&auto=format&fit=crop&q=80' }}" class="w-10 h-10 object-cover rounded-lg border border-slate-700">
                                <div>
                                    <h5 class="font-semibold text-white">{{ $it->product_name }}</h5>
                                    <p class="text-[10px] text-slate-400 font-mono">Qty: {{ $it->quantity }} • {{ $it->variant_info }}</p>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-cyan-300">{{ \App\Helpers\BanglaHelper::formatTaka($it->total) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs font-mono">
                    <span class="text-slate-400">Total with Shipping: <b class="text-white">{{ \App\Helpers\BanglaHelper::formatTaka($ord->total_amount) }}</b></span>
                    <span class="text-pink-400 uppercase font-bold">Paid via {{ $ord->payment_method }}</span>
                </div>
            </div>
        @empty
            <div class="glass-card rounded-3xl p-12 text-center space-y-3">
                <i data-lucide="package-open" class="w-12 h-12 text-slate-500 mx-auto"></i>
                <h4 class="font-cyber font-bold text-white text-base">No Orders Placed Yet</h4>
                <p class="text-xs text-slate-400">You haven't placed any orders with this account yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Tab 2: Wishlist -->
    <div x-show="currentTab === 'wishlist'" x-cloak class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($wishlistItems as $wItem)
                @if($wItem->product)
                    <div class="glass-card rounded-2xl p-4 flex flex-col justify-between">
                        <a href="{{ route('product.show', $wItem->product->slug) }}">
                            <img src="{{ $wItem->product->thumbnail }}" class="w-full h-44 object-cover rounded-xl mb-3">
                            <h4 class="font-semibold text-xs text-white truncate">{{ $wItem->product->name }}</h4>
                            <span class="font-mono font-bold text-sm text-cyan-300 mt-1 block">{{ \App\Helpers\BanglaHelper::formatTaka($wItem->product->effective_price) }}</span>
                        </a>
                    </div>
                @endif
            @empty
                <div class="col-span-full glass-card rounded-3xl p-12 text-center space-y-3">
                    <i data-lucide="heart" class="w-12 h-12 text-slate-500 mx-auto"></i>
                    <h4 class="font-cyber font-bold text-white text-base">Your Wishlist is Empty</h4>
                    <p class="text-xs text-slate-400">Add products to your wishlist while browsing to save them for later.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tab 3: Saved Addresses -->
    <div x-show="currentTab === 'addresses'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($addresses as $addr)
                <div class="glass-card rounded-2xl p-5 border border-slate-800 space-y-3 relative">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                            {{ $addr->label }}
                        </span>
                        <form action="{{ route('customer.address.delete', $addr->id) }}" method="POST" onsubmit="return confirm('এই ঠিকানা মুছে ফেলতে চান?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-500 hover:text-red-400 p-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                    <div class="space-y-1 text-xs">
                        <p class="font-bold text-white">{{ $addr->name }}</p>
                        <p class="text-slate-300 font-mono">{{ $addr->phone }}</p>
                        <p class="text-slate-400 leading-relaxed">{{ $addr->address }}, {{ $addr->district }}</p>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 p-8 rounded-2xl bg-slate-900/60 border border-slate-800 text-center text-xs text-slate-500">
                    কোনো সংরক্ষিত ঠিকানা নেই। নিচের ফর্ম থেকে নতুন ঠিকানা যোগ করুন। 📍
                </div>
            @endforelse
        </div>

        <!-- Add Address Form -->
        <div class="glass-card rounded-2xl p-6 sm:p-8 max-w-xl space-y-4">
            <h4 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">নতুন ডেলিভারি ঠিকানা যোগ করুন</h4>
            
            <form action="{{ route('customer.address.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300">ঠিকানার লেবেল (Label) *</label>
                        <select name="label" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                            <option value="Home">Home (বাসা)</option>
                            <option value="Office">Office (অফিস)</option>
                            <option value="Other">Other (অন্যান্য)</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300">প্রাপকের নাম *</label>
                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300">মোবাইল নম্বর *</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" required placeholder="017XXXXXXXX" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300">জেলা *</label>
                        <select name="district" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                            @foreach($districts as $key => $d)
                                <option value="{{ $key }}">{{ $key }} ({{ $d['name_bn'] }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">সম্পূর্ণ ঠিকানা (বাসা/রোড/থানা) *</label>
                    <textarea name="address" rows="2" required placeholder="e.g. House 14, Road 4, Sector 7, Uttara, Dhaka" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white"></textarea>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    ঠিকানা সংরক্ষণ করুন 📍
                </button>
            </form>
        </div>
    </div>

    <!-- Tab 4: Profile Settings Form -->
    <div x-show="currentTab === 'profile'" x-cloak>
        <div class="glass-card rounded-2xl p-6 sm:p-8 max-w-2xl space-y-6">
            <h3 class="font-cyber font-bold text-base text-white uppercase tracking-wider">Update Personal Profile</h3>
            
            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Phone Number (Bangladesh)</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" placeholder="01711000111" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
                </div>

                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Default District</label>
                    <select name="district" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                        @foreach($districts as $key => $d)
                            <option value="{{ $key }}" {{ $user->district == $key ? 'selected' : '' }}>{{ $key }} ({{ $d['name_bn'] }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Default Street Address</label>
                    <textarea name="address" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">{{ $user->address }}</textarea>
                </div>

                <button type="submit" class="px-6 py-3 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan">
                    Save Profile Changes
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
