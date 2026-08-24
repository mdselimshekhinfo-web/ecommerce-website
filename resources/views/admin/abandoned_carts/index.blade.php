@extends('layouts.admin')

@section('page-title', 'অ্যাবান্ডনড কার্ট রিকভারি ও সেলস বুস্টার (Abandoned Cart Recovery)')

@section('content')
<div class="space-y-8">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL ABANDONED CARTS</span>
            <p class="font-cyber font-bold text-2xl text-white">{{ $totalAbandoned }} টি কার্ট</p>
            <p class="text-[11px] text-slate-400 font-mono">অসম্পূর্ণ রেখে যাওয়া অর্ডার</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-pink-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">RECOVERABLE REVENUE (সম্ভাব্য সেলস)</span>
            <p class="font-mono font-black text-2xl text-pink-400">{{ \App\Helpers\BanglaHelper::formatTaka($potentialRevenue) }}</p>
            <p class="text-[11px] text-slate-400 font-mono">রিকভারিযোগ্য সেলসের পরিমাণ</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">RECOVERED ORDERS</span>
            <p class="font-cyber font-bold text-2xl text-emerald-300">{{ $recoveredCount }} টি সফল</p>
            <p class="text-[11px] text-slate-400 font-mono">মেসেজ পাঠিয়ে সম্পন্নকৃত অর্ডার</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.abandoned_carts.index') }}" method="GET" class="flex items-center space-x-3 w-full sm:w-auto font-mono text-xs">
            <select name="status" class="bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none">
                <option value="">সকল কার্ট</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ পেন্ডিং (Pending)</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>📱 মেসেজ পাঠানো হয়েছে (Contacted)</option>
                <option value="recovered" {{ request('status') === 'recovered' ? 'selected' : '' }}>✓ রিকভার সম্পন্ন (Recovered)</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold">
                Filter
            </button>
        </form>

        <span class="text-xs font-mono text-slate-400">
            💡 কাস্টমারকে হোয়াটসঅ্যাপে বিশেষ ৫%-১০% ডিসকাউন্ট ভাউচার পাঠালে সেলস রিকভারি সম্ভাবনা ৭০% বৃদ্ধি পায়।
        </span>
    </div>

    <!-- Abandoned Carts Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">কাস্টমার</th>
                        <th class="pb-3">কার্টের প্রোডাক্টসমূহ</th>
                        <th class="pb-3">মোট মূল্য</th>
                        <th class="pb-3">সর্বশেষ একটিভ</th>
                        <th class="pb-3">রিকভারি স্ট্যাটাস</th>
                        <th class="pb-3 text-right">১-ক্লিক ফলো-আপ অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($carts as $cart)
                        @php
                            $phoneClean = preg_replace('/[^0-9]/', '', $cart->customer_phone);
                            if (strlen($phoneClean) === 11 && str_starts_with($phoneClean, '01')) {
                                $phoneWithCode = '88' . $phoneClean;
                            } else {
                                $phoneWithCode = $phoneClean;
                            }
                            $waMessage = rawurlencode("👋 হ্যালো " . ($cart->customer_name ?: 'প্রিয় গ্রাহক') . "! NEXUS DOKAN-এ আপনার কার্টে থাকা প্রোডাক্টগুলোর জন্য আমরা স্পেশাল ১০% ডিসকাউন্ট ভাউচার দিচ্ছি। কুপন কোড: CYBER10। অর্ডারটি সম্পন্ন করতে এখানে ক্লিক করুন: " . route('shop.index'));
                        @endphp
                        <tr>
                            <td class="py-3.5">
                                <span class="font-sans font-bold text-white block">{{ $cart->customer_name ?: 'Anonymous Shopper' }}</span>
                                <span class="text-[11px] text-cyan-300">{{ $cart->customer_phone ?: 'No phone' }}</span>
                                <span class="text-[10px] text-slate-500 block">{{ $cart->customer_email }}</span>
                            </td>
                            <td class="py-3.5">
                                <div class="space-y-1 max-w-xs">
                                    @if(is_array($cart->items))
                                        @foreach($cart->items as $it)
                                            <div class="text-slate-300 truncate">
                                                • {{ $it['name'] ?? 'Product' }} (x{{ $it['quantity'] ?? 1 }})
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-slate-500">Items recorded</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 font-bold text-white text-sm">{{ \App\Helpers\BanglaHelper::formatTaka($cart->subtotal) }}</td>
                            <td class="py-3.5 text-slate-400 text-[11px]">
                                {{ $cart->last_active_at ? $cart->last_active_at->diffForHumans() : $cart->created_at->diffForHumans() }}
                            </td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $cart->recovery_status === 'recovered' ? 'bg-emerald-500/20 text-emerald-300' : ($cart->recovery_status === 'contacted' ? 'bg-cyan-500/20 text-cyan-300' : 'bg-amber-500/20 text-amber-300') }}">
                                    {{ $cart->recovery_status }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right space-x-2">
                                @if($cart->customer_phone)
                                    <!-- WhatsApp 1-Click Button -->
                                    <a href="https://wa.me/{{ $phoneWithCode }}?text={{ $waMessage }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 font-bold inline-flex items-center space-x-1 transition-all" title="Send WhatsApp Message">
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                        <span>WhatsApp 💬</span>
                                    </a>

                                    <!-- SMS 1-Click Button -->
                                    <form action="{{ route('admin.abandoned_carts.send_sms', $cart->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-300 border border-cyan-500/40 font-bold inline-flex items-center space-x-1 transition-all" title="Send SMS with Discount Coupon">
                                            <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                            <span>SMS কুপন</span>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">কোনো অসম্পূর্ণ কার্ট নেই।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $carts->links() }}
        </div>
    </div>

</div>
@endsection
