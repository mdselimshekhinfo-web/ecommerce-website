@extends('layouts.app')

@section('title', ($themeSettings['site_name'] ?? 'NEXUS DOKAN') . ' // ' . \App\Helpers\LocalizationHelper::get('hero_badge'))

@section('content')

@php
    $heroSec = $sections['hero'] ?? null;
    $heroActive = $heroSec?->is_active ?? true;
    $hContent = $heroSec?->content ?? [];

    $flashSec = $sections['flash_sale'] ?? null;
    $flashActive = $flashSec?->is_active ?? true;
    $fContent = $flashSec?->content ?? [];

    $catSec = $sections['categories'] ?? null;
    $catActive = $catSec?->is_active ?? true;
    $cContent = $catSec?->content ?? [];

    $trendSec = $sections['trending'] ?? null;
    $trendActive = $trendSec?->is_active ?? true;
    $tContent = $trendSec?->content ?? [];

    $promoSec = $sections['promo_banner'] ?? null;
    $promoActive = $promoSec?->is_active ?? true;
    $pContent = $promoSec?->content ?? [];

    $trustSec = $sections['trust_badges'] ?? null;
    $trustActive = $trustSec?->is_active ?? true;
    $pkContent = $trustSec?->content ?? [];

    $revSec = $sections['reviews'] ?? null;
    $revActive = $revSec?->is_active ?? true;
    $rContent = $revSec?->content ?? [];
@endphp

<!-- 1. Dynamic Hero Hologram Banner -->
@if($heroActive)
<section class="relative overflow-hidden pt-12 pb-20 md:py-24">
    <!-- Ambient Blur Orbs -->
    <div class="absolute top-1/4 left-10 w-96 h-96 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-600/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left: Hero Headline & CTA -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-400/30 text-cyan-300 text-xs font-mono font-bold tracking-wider animate-float shadow-neon-cyan">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                    <span>{{ \App\Helpers\LocalizationHelper::get('hero_badge') }}</span>
                </div>

                <h1 class="font-cyber font-black text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-tight">
                    {{ \App\Helpers\LocalizationHelper::get('hero_title_line_1') }} <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-200 to-pink-500">
                        {{ \App\Helpers\LocalizationHelper::get('hero_title_gradient') }}
                    </span>
                </h1>

                <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    {{ \App\Helpers\LocalizationHelper::get('hero_subtitle') }}
                </p>

                <!-- Action CTA Buttons -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                    <a href="{{ route('shop.index') }}" class="px-8 py-4 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan flex items-center space-x-2">
                        <span>{{ \App\Helpers\LocalizationHelper::get('hero_btn_shop') }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>

                    <button @click="spinModal = true" class="px-6 py-4 rounded-xl glass-panel hover:border-amber-400 text-amber-300 font-cyber font-bold text-xs uppercase tracking-wider flex items-center space-x-2 transition-all hover:scale-105">
                        <i data-lucide="sparkles" class="w-4 h-4 text-amber-400"></i>
                        <span>{{ \App\Helpers\LocalizationHelper::get('hero_btn_spin') }}</span>
                    </button>
                </div>

                <!-- Trust Stats -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-800/80 max-w-md mx-auto lg:mx-0 text-left">
                    <div>
                        <div class="font-cyber font-black text-xl text-cyan-400">{{ \App\Helpers\LocalizationHelper::get('stat_customers_count') }}</div>
                        <div class="text-[11px] text-slate-400">{{ \App\Helpers\LocalizationHelper::get('stat_customers_label') }}</div>
                    </div>
                    <div>
                        <div class="font-cyber font-black text-xl text-pink-400">{{ \App\Helpers\LocalizationHelper::get('stat_delivery_count') }}</div>
                        <div class="text-[11px] text-slate-400">{{ \App\Helpers\LocalizationHelper::get('stat_delivery_label') }}</div>
                    </div>
                    <div>
                        <div class="font-cyber font-black text-xl text-emerald-400">{{ \App\Helpers\LocalizationHelper::get('stat_genuine_count') }}</div>
                        <div class="text-[11px] text-slate-400">{{ \App\Helpers\LocalizationHelper::get('stat_genuine_label') }}</div>
                    </div>
                </div>

            </div>

            <!-- Right: Featured Hero Hologram Product Card -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-sm rounded-3xl p-1 bg-gradient-to-b from-cyan-500/40 via-purple-500/20 to-pink-500/40 shadow-2xl">
                    <div class="rounded-[22px] bg-slate-950 p-6 space-y-5 relative overflow-hidden">
                        
                        <!-- Top Tag -->
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-md bg-pink-500/20 border border-pink-500/40 text-pink-400 font-mono text-[10px] font-bold uppercase">
                                {{ \App\Helpers\LocalizationHelper::get('featured_card_badge') }}
                            </span>
                            <span class="text-xs font-mono text-cyan-300 font-bold">{{ \App\Helpers\LocalizationHelper::get('featured_card_price') }}</span>
                        </div>

                        <!-- Product Visual -->
                        <div class="relative group my-2">
                            <img src="{{ $hContent['featured_card_img'] ?? 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600&auto=format&fit=crop&q=80' }}" 
                                 class="w-full h-64 object-cover rounded-2xl border border-slate-800 group-hover:scale-105 transition-transform duration-500">
                            
                            <div class="absolute bottom-3 left-3 right-3 bg-slate-900/90 backdrop-blur-md p-2.5 rounded-xl border border-cyan-500/20 flex items-center justify-between text-xs">
                                <div>
                                    <p class="font-bold text-white">{{ \App\Helpers\LocalizationHelper::get('featured_card_title') }}</p>
                                    <p class="text-[10px] text-slate-400">{{ \App\Helpers\LocalizationHelper::get('featured_card_sub') }}</p>
                                </div>
                                <span class="text-emerald-400 font-bold text-[10px] font-mono">{{ \App\Helpers\LocalizationHelper::get('in_stock') }}</span>
                            </div>
                        </div>

                        <!-- Mini Specs -->
                        <div class="grid grid-cols-2 gap-2 text-[11px] font-mono text-slate-300">
                            <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 flex items-center space-x-1.5">
                                <i data-lucide="bluetooth" class="w-3.5 h-3.5 text-cyan-400"></i>
                                <span>{{ \App\Helpers\LocalizationHelper::get('featured_card_spec1') }}</span>
                            </div>
                            <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 flex items-center space-x-1.5">
                                <i data-lucide="battery-charging" class="w-3.5 h-3.5 text-pink-400"></i>
                                <span>{{ \App\Helpers\LocalizationHelper::get('featured_card_spec2') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('shop.index') }}" class="block w-full py-3 rounded-xl cyber-btn text-center text-xs font-cyber font-bold tracking-wider shadow-neon-cyan">
                            {{ \App\Helpers\LocalizationHelper::get('featured_card_btn') }}
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endif

<!-- 2. Dynamic Live Flash Sale Matrix -->
@if($flashActive && $flashDeals->isNotEmpty())
<section class="py-12 bg-slate-950/70 border-y border-slate-800/80 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-pink-500/20 border border-pink-500/40 flex items-center justify-center shadow-neon-pink">
                    <i data-lucide="flame" class="w-6 h-6 text-pink-400 animate-pulse"></i>
                </div>
                <div>
                    <h2 class="font-cyber font-black text-2xl text-white tracking-wide">{{ \App\Helpers\LocalizationHelper::get('flash_deals_title') }}</h2>
                    <p class="text-xs text-slate-400">{{ \App\Helpers\LocalizationHelper::get('flash_deals_sub') }}</p>
                </div>
            </div>

            <!-- Countdown Timer -->
            <div class="flex items-center space-x-2 font-mono text-xs" x-data="countdownTimer()">
                <span class="text-slate-400 font-semibold mr-1">ENDS IN:</span>
                <div class="flex items-center space-x-1.5">
                    <span class="px-2.5 py-1.5 rounded-lg bg-slate-900 border border-cyan-500/30 font-bold text-cyan-300" x-text="hours">08</span>
                    <span class="text-slate-500">:</span>
                    <span class="px-2.5 py-1.5 rounded-lg bg-slate-900 border border-cyan-500/30 font-bold text-cyan-300" x-text="minutes">42</span>
                    <span class="text-slate-500">:</span>
                    <span class="px-2.5 py-1.5 rounded-lg bg-pink-500/20 border border-pink-500/40 font-bold text-pink-400 animate-pulse" x-text="seconds">19</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($flashDeals as $product)
                <div class="glass-card rounded-2xl p-4 flex flex-col justify-between relative group">
                    <div class="absolute top-3 left-3 z-10">
                        <span class="px-2 py-1 rounded-md bg-pink-600 text-white font-mono text-[10px] font-extrabold shadow-neon-pink">
                            -{{ $product->discount_percent }}% {{ \App\Helpers\LocalizationHelper::get('discount') }}
                        </span>
                    </div>

                    <div class="relative overflow-hidden rounded-xl bg-slate-900/80 mb-3">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->thumbnail }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>
                    </div>

                    <div class="space-y-2 flex-1">
                        <div class="text-[11px] font-mono text-cyan-400 uppercase tracking-wider">{{ $product->category->name }}</div>
                        <a href="{{ route('product.show', $product->slug) }}" class="block font-semibold text-sm text-white group-hover:text-cyan-300 transition-colors line-clamp-1">
                            {{ $product->name }}
                        </a>

                        <div class="flex items-center space-x-2 pt-1">
                            <span class="font-mono font-bold text-base text-cyan-300">{{ \App\Helpers\BanglaHelper::formatTaka($product->effective_price) }}</span>
                            @if($product->sale_price)
                                <span class="font-mono text-xs text-slate-500 line-through">{{ \App\Helpers\BanglaHelper::formatTaka($product->price) }}</span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-4" @submit.prevent="quickAddToCart($event)">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-full py-2.5 rounded-xl cyber-btn text-xs font-bold font-cyber flex items-center justify-center space-x-2 shadow-neon-cyan">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            <span>{{ \App\Helpers\LocalizationHelper::get('add_to_cart') }}</span>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

    </div>
</section>
@endif

<!-- 3. Dynamic Category Showcase Grid -->
@if($catActive)
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="font-cyber font-black text-2xl sm:text-3xl text-white tracking-wide">
                {{ \App\Helpers\LocalizationHelper::get('categories_title') }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-2">
                {{ \App\Helpers\LocalizationHelper::get('categories_sub') }}
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
            @foreach($featuredCategories as $cat)
                @php
                    $isBn = \App\Helpers\LocalizationHelper::getLocale() === 'bn';
                    $catDisplayName = ($isBn && !empty($cat->name_bn)) ? $cat->name_bn : $cat->name;
                @endphp
                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" 
                   class="glass-card rounded-2xl p-5 text-center flex flex-col items-center justify-center space-y-3 group hover:border-cyan-400">
                    <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-700 flex items-center justify-center group-hover:border-cyan-400/50 group-hover:bg-cyan-500/10 transition-all shadow-inner">
                        <i data-lucide="{{ $cat->icon ?: 'package' }}" class="w-7 h-7 text-cyan-400 group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-bold text-white group-hover:text-cyan-300">{{ $catDisplayName }}</h4>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>
@endif

<!-- 4. Dynamic Trending Cyber Gear Grid -->
@if($trendActive)
<section class="py-16 bg-slate-950/60 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="font-cyber font-black text-2xl sm:text-3xl text-white tracking-wide">
                    {{ \App\Helpers\LocalizationHelper::get('top_products_title') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">{{ \App\Helpers\LocalizationHelper::get('top_products_sub') }}</p>
            </div>
            <a href="{{ route('shop.index') }}" class="hidden sm:inline-flex items-center space-x-1 text-xs font-mono font-bold text-cyan-400 hover:text-cyan-300">
                <span>{{ \App\Helpers\LocalizationHelper::get('nav_shop') }}</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($trendingProducts as $product)
                <div class="glass-card rounded-2xl p-4 flex flex-col justify-between relative group">
                    @if($product->badge)
                        <div class="absolute top-3 left-3 z-10">
                            <span class="px-2 py-0.5 rounded-md bg-cyan-500/20 border border-cyan-400/40 text-cyan-300 font-mono text-[9px] font-bold uppercase">
                                {{ $product->badge }}
                            </span>
                        </div>
                    @endif

                    <div class="relative overflow-hidden rounded-xl bg-slate-900 mb-3">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->thumbnail }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>
                    </div>

                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center justify-between text-[10px] text-slate-400 font-mono">
                            <span>{{ $product->category->name }}</span>
                            <span class="text-amber-400 flex items-center">
                                <i data-lucide="star" class="w-3 h-3 fill-amber-400 mr-0.5"></i> {{ $product->rating }}
                            </span>
                        </div>

                        <a href="{{ route('product.show', $product->slug) }}" class="block font-semibold text-xs sm:text-sm text-white group-hover:text-cyan-300 transition-colors line-clamp-1">
                            {{ $product->name }}
                        </a>

                        <div class="flex items-center space-x-2 pt-2">
                            <span class="font-mono font-bold text-sm sm:text-base text-cyan-300">
                                {{ \App\Helpers\BanglaHelper::formatTaka($product->effective_price) }}
                            </span>
                            @if($product->sale_price)
                                <span class="font-mono text-xs text-slate-500 line-through">
                                    {{ \App\Helpers\BanglaHelper::formatTaka($product->price) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-4" @submit.prevent="quickAddToCart($event)">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-900 border border-cyan-500/30 hover:border-cyan-400 text-xs font-bold text-cyan-300 hover:text-white hover:bg-cyan-500/20 transition-all flex items-center justify-center space-x-1.5">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span>{{ \App\Helpers\LocalizationHelper::get('add_to_cart') }}</span>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

    </div>
</section>
@endif

<!-- 5. Dynamic Cyber Callout Promo Banner -->
@if($promoActive)
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl p-8 sm:p-12 overflow-hidden bg-gradient-to-r from-purple-950 via-slate-900 to-cyan-950 border border-purple-500/40 shadow-2xl">
            <div class="relative z-10 max-w-2xl space-y-4 text-center sm:text-left">
                <span class="px-3 py-1 rounded-full bg-pink-500/20 text-pink-300 border border-pink-500/40 text-xs font-mono font-bold uppercase">
                    {{ \App\Helpers\LocalizationHelper::get('promo_banner_badge') }}
                </span>
                <h3 class="font-cyber font-black text-2xl sm:text-3xl text-white">
                    {{ \App\Helpers\LocalizationHelper::get('promo_banner_title') }}
                </h3>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    {{ \App\Helpers\LocalizationHelper::get('promo_banner_sub') }}
                </p>
                <div class="pt-2">
                    <a href="{{ route('shop.index') }}" class="inline-block px-8 py-3.5 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan">
                        {{ \App\Helpers\LocalizationHelper::get('promo_banner_btn') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- 6. Dynamic Bangladesh Trust & Perks Section -->
@if($trustActive)
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <div class="glass-card rounded-2xl p-6 space-y-3">
                <div class="w-12 h-12 rounded-xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center">
                    <i data-lucide="truck" class="w-6 h-6 text-cyan-400"></i>
                </div>
                <h4 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('trust_1_title') }}</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    {{ \App\Helpers\LocalizationHelper::get('trust_1_sub') }}
                </p>
            </div>

            <div class="glass-card rounded-2xl p-6 space-y-3">
                <div class="w-12 h-12 rounded-xl bg-pink-500/20 border border-pink-500/40 flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-6 h-6 text-pink-400"></i>
                </div>
                <h4 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('trust_3_title') }}</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    {{ \App\Helpers\LocalizationHelper::get('trust_3_sub') }}
                </p>
            </div>

            <div class="glass-card rounded-2xl p-6 space-y-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-6 h-6 text-emerald-400"></i>
                </div>
                <h4 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('trust_2_title') }}</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    {{ \App\Helpers\LocalizationHelper::get('trust_2_sub') }}
                </p>
            </div>

            <div class="glass-card rounded-2xl p-6 space-y-3">
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center">
                    <i data-lucide="sparkles" class="w-6 h-6 text-amber-400"></i>
                </div>
                <h4 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('trust_4_title') }}</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    {{ \App\Helpers\LocalizationHelper::get('trust_4_sub') }}
                </p>
            </div>

        </div>

    </div>
</section>
@endif

<!-- 7. Dynamic Customer Reviews Section -->
@if($revActive)
<section class="py-16 bg-slate-950/70 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-xl mx-auto mb-12">
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 text-xs font-mono font-bold mb-2">
                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                <span>{{ \App\Helpers\LocalizationHelper::get('verified_buyer') }}</span>
            </div>
            <h2 class="font-cyber font-black text-2xl sm:text-3xl text-white">
                {{ \App\Helpers\LocalizationHelper::get('reviews_title') }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($recentReviews as $rev)
                <div class="glass-card rounded-2xl p-5 space-y-3 flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-1 text-amber-400">
                            @for($i = 0; $i < $rev->rating; $i++)
                                <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400"></i>
                            @endfor
                        </div>
                        <p class="text-xs text-slate-300 italic leading-relaxed">
                            "{{ $rev->comment }}"
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                        <div>
                            <h5 class="font-bold text-white">{{ $rev->customer_name }}</h5>
                            <p class="text-[10px] text-slate-400">{{ $rev->customer_location }}</p>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-mono">{{ \App\Helpers\LocalizationHelper::get('verified_buyer') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
    function countdownTimer() {
        return {
            hours: '08',
            minutes: '42',
            seconds: '19',
            totalSeconds: 31339,

            init() {
                setInterval(() => {
                    if (this.totalSeconds > 0) {
                        this.totalSeconds--;
                        const h = Math.floor(this.totalSeconds / 3600);
                        const m = Math.floor((this.totalSeconds % 3600) / 60);
                        const s = this.totalSeconds % 60;
                        this.hours = String(h).padStart(2, '0');
                        this.minutes = String(m).padStart(2, '0');
                        this.seconds = String(s).padStart(2, '0');
                    }
                }, 1000);
            }
        }
    }

    function quickAddToCart(e) {
        const form = e.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const app = Alpine.$data(document.querySelector('[x-data="globalApp()"]'));
                if (app) {
                    app.openCartDrawer();
                }
            }
        });
    }
</script>
@endpush
