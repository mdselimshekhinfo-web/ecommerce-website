@extends('layouts.admin')

@section('page-title', 'Cyber Theme & Visual Section Builder')

@section('content')
<div class="space-y-8" x-data="themeBuilder()">

    <!-- Header Actions Bar -->
    <div class="admin-glass rounded-3xl p-6 border border-pink-500/30 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-pink-500/20 text-pink-300 border border-pink-500/40 text-[11px] font-mono font-bold mb-2">
                <i data-lucide="palette" class="w-3.5 h-3.5"></i>
                <span>VISUAL ELEMENTOR-STYLE THEME CUSTOMIZER</span>
            </div>
            <h1 class="font-cyber font-black text-2xl text-white tracking-wide">
                THEME & SECTION BUILDER
            </h1>
            <p class="text-xs text-slate-400">Edit branding, colors, tickers, and customize home page sections without touching any code.</p>
        </div>

        <div class="flex items-center space-x-3">
            <form action="{{ route('admin.theme.reset_defaults') }}" method="POST" onsubmit="return confirm('Reset all settings & section contents to original Cyber Defaults?')">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-red-400 text-slate-400 hover:text-red-300 text-xs font-mono font-bold transition-all">
                    Reset Defaults
                </button>
            </form>

            <a href="{{ route('home') }}" target="_blank" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan flex items-center space-x-1.5 hover:scale-105 transition-all">
                <span>View Live Store ↗</span>
            </a>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex items-center space-x-3 overflow-x-auto pb-2">
        <button @click="activeTab = 'sections'" :class="activeTab === 'sections' ? 'bg-gradient-to-r from-cyan-500 to-indigo-600 text-slate-950 font-bold shadow-neon-cyan' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" class="px-6 py-3 rounded-2xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="layers" class="w-4 h-4"></i>
            <span>Visual Sections Builder ({{ count($sections) }})</span>
        </button>

        <button @click="activeTab = 'global'" :class="activeTab === 'global' ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold shadow-neon-pink' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" class="px-6 py-3 rounded-2xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="sliders" class="w-4 h-4"></i>
            <span>Global Theme & Colors</span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: VISUAL SECTION BUILDER (ELEMENTOR) -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'sections'" class="space-y-6">
        
        <!-- Accordion Section 1: Hero Banner -->
        @php $hero = $sections['hero'] ?? null; $hContent = $hero?->content ?? []; @endphp
        <div class="admin-glass rounded-3xl border border-slate-800 overflow-hidden" x-data="{ open: true }">
            <div class="p-6 bg-slate-950/60 border-b border-slate-800 flex items-center justify-between cursor-pointer" @click="open = !open">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-cyber font-bold text-xs">
                        01
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">Hero Hologram Banner</h3>
                        <p class="text-[10px] font-mono text-slate-400">Headline, CTA buttons, floating stats, and 3D preview card</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3" @click.stop>
                    <span class="text-xs font-mono font-bold {{ ($hero?->is_active ?? true) ? 'text-emerald-400' : 'text-slate-500' }}">
                        {{ ($hero?->is_active ?? true) ? '● VISIBLE' : '○ HIDDEN' }}
                    </span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </div>
            </div>

            <div x-show="open" class="p-6 sm:p-8 space-y-6">
                <form action="{{ route('admin.theme.update_section', 'hero') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="flex items-center space-x-2 pb-2">
                        <label class="flex items-center space-x-2 text-xs font-mono text-cyan-300 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ ($hero?->is_active ?? true) ? 'checked' : '' }} class="rounded text-cyan-500">
                            <span class="font-bold">Show This Section on Home Page</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                        <div class="space-y-1.5">
                            <label class="text-slate-300">Top Neon Badge Text</label>
                            <input type="text" name="content[badge]" value="{{ $hContent['badge'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-slate-300">Title Line 1</label>
                            <input type="text" name="content[title_line_1]" value="{{ $hContent['title_line_1'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-slate-300">Title Gradient (Glowing Highlight)</label>
                            <input type="text" name="content[title_gradient]" value="{{ $hContent['title_gradient'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-slate-300">Button 1 Text & Link</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="content[btn1_text]" value="{{ $hContent['btn1_text'] ?? '' }}" placeholder="Text" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                                <input type="text" name="content[btn1_link]" value="{{ $hContent['btn1_link'] ?? '' }}" placeholder="URL" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 text-xs font-mono">
                        <label class="text-slate-300">Subtitle Description</label>
                        <textarea name="content[subtitle]" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">{{ $hContent['subtitle'] ?? '' }}</textarea>
                    </div>

                    <!-- Floating Stats Matrix -->
                    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-3">
                        <h4 class="font-cyber font-bold text-xs text-cyan-400">Hero Trust Stats Matrix</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs font-mono">
                            <div class="space-y-1">
                                <label class="text-slate-400 text-[10px]">Stat 1 (e.g. 50K+ / BD Customers)</label>
                                <input type="text" name="content[stat1_num]" value="{{ $hContent['stat1_num'] ?? '50K+' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                                <input type="text" name="content[stat1_label]" value="{{ $hContent['stat1_label'] ?? 'BD Customers' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-300 mt-1">
                            </div>
                            <div class="space-y-1">
                                <label class="text-slate-400 text-[10px]">Stat 2 (e.g. 24H / Dhaka Express)</label>
                                <input type="text" name="content[stat2_num]" value="{{ $hContent['stat2_num'] ?? '24H' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                                <input type="text" name="content[stat2_label]" value="{{ $hContent['stat2_label'] ?? 'Dhaka Express' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-300 mt-1">
                            </div>
                            <div class="space-y-1">
                                <label class="text-slate-400 text-[10px]">Stat 3 (e.g. 100% / Genuine Tech)</label>
                                <input type="text" name="content[stat3_num]" value="{{ $hContent['stat3_num'] ?? '100%' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                                <input type="text" name="content[stat3_label]" value="{{ $hContent['stat3_label'] ?? 'Genuine Tech' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-300 mt-1">
                            </div>
                        </div>
                    </div>

                    <!-- Hero Featured Visual Card -->
                    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-3">
                        <h4 class="font-cyber font-bold text-xs text-pink-400">Hero 3D Featured Hologram Card</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs font-mono">
                            <div>
                                <label class="text-slate-400 text-[10px]">Card Title</label>
                                <input type="text" name="content[featured_card_title]" value="{{ $hContent['featured_card_title'] ?? '' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                            </div>
                            <div>
                                <label class="text-slate-400 text-[10px]">Price Tag</label>
                                <input type="text" name="content[featured_card_price]" value="{{ $hContent['featured_card_price'] ?? '' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                            </div>
                            <div>
                                <label class="text-slate-400 text-[10px]">Image URL</label>
                                <input type="url" name="content[featured_card_img]" value="{{ $hContent['featured_card_img'] ?? '' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan transition-all">
                        Save Hero Section 💾
                    </button>
                </form>
            </div>
        </div>

        <!-- Accordion Section 2: Flash Sale Matrix -->
        @php $flash = $sections['flash_sale'] ?? null; $fContent = $flash?->content ?? []; @endphp
        <div class="admin-glass rounded-3xl border border-slate-800 overflow-hidden" x-data="{ open: false }">
            <div class="p-6 bg-slate-950/60 border-b border-slate-800 flex items-center justify-between cursor-pointer" @click="open = !open">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-pink-500/20 text-pink-400 flex items-center justify-center font-cyber font-bold text-xs">
                        02
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">Flash Sale Matrix</h3>
                        <p class="text-[10px] font-mono text-slate-400">Countdown timer, discounts, and claimed stock meter</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3" @click.stop>
                    <span class="text-xs font-mono font-bold {{ ($flash?->is_active ?? true) ? 'text-emerald-400' : 'text-slate-500' }}">
                        {{ ($flash?->is_active ?? true) ? '● VISIBLE' : '○ HIDDEN' }}
                    </span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </div>
            </div>

            <div x-show="open" class="p-6 sm:p-8 space-y-6">
                <form action="{{ route('admin.theme.update_section', 'flash_sale') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <label class="flex items-center space-x-2 text-xs font-mono text-pink-300 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ ($flash?->is_active ?? true) ? 'checked' : '' }} class="rounded text-pink-500">
                        <span class="font-bold">Show Flash Sale Matrix on Home Page</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                        <div class="space-y-1.5">
                            <label class="text-slate-300">Section Title</label>
                            <input type="text" name="content[title]" value="{{ $fContent['title'] ?? 'FLASH SALE MATRIX' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-slate-300">Subtitle Description</label>
                            <input type="text" name="content[subtitle]" value="{{ $fContent['subtitle'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-pink-500 hover:bg-pink-400 text-white font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-pink transition-all">
                        Save Flash Sale Section 💾
                    </button>
                </form>
            </div>
        </div>

        <!-- Accordion Section 3: Callout Promo Banner -->
        @php $promo = $sections['promo_banner'] ?? null; $pContent = $promo?->content ?? []; @endphp
        <div class="admin-glass rounded-3xl border border-slate-800 overflow-hidden" x-data="{ open: false }">
            <div class="p-6 bg-slate-950/60 border-b border-slate-800 flex items-center justify-between cursor-pointer" @click="open = !open">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-cyber font-bold text-xs">
                        03
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">Cyber Callout Promo Banner</h3>
                        <p class="text-[10px] font-mono text-slate-400">Promotional discount banner with promo code & direct CTA</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3" @click.stop>
                    <span class="text-xs font-mono font-bold {{ ($promo?->is_active ?? true) ? 'text-emerald-400' : 'text-slate-500' }}">
                        {{ ($promo?->is_active ?? true) ? '● VISIBLE' : '○ HIDDEN' }}
                    </span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </div>
            </div>

            <div x-show="open" class="p-6 sm:p-8 space-y-6">
                <form action="{{ route('admin.theme.update_section', 'promo_banner') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <label class="flex items-center space-x-2 text-xs font-mono text-purple-300 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ ($promo?->is_active ?? true) ? 'checked' : '' }} class="rounded text-purple-500">
                        <span class="font-bold">Show Promo Callout Banner</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                        <div class="space-y-1.5">
                            <label class="text-slate-300">Banner Headline</label>
                            <input type="text" name="content[title]" value="{{ $pContent['title'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-slate-300">Coupon Badge Code</label>
                            <input type="text" name="content[coupon_badge]" value="{{ $pContent['coupon_badge'] ?? 'CYBER10' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white uppercase">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-slate-300">Button Text</label>
                            <input type="text" name="content[btn_text]" value="{{ $pContent['btn_text'] ?? 'Shop Cyber Audio & Keyboards' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-slate-300">Button URL</label>
                            <input type="text" name="content[btn_link]" value="{{ $pContent['btn_link'] ?? '/shop' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>
                    </div>

                    <div class="space-y-1.5 text-xs font-mono">
                        <label class="text-slate-300">Banner Description</label>
                        <input type="text" name="content[subtitle]" value="{{ $pContent['subtitle'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>

                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-500 hover:bg-purple-400 text-white font-cyber font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                        Save Promo Banner 💾
                    </button>
                </form>
            </div>
        </div>

        <!-- Accordion Section 4: Bangladesh Trust & Perks -->
        @php $perks = $sections['trust_badges'] ?? null; $pkContent = $perks?->content ?? []; @endphp
        <div class="admin-glass rounded-3xl border border-slate-800 overflow-hidden" x-data="{ open: false }">
            <div class="p-6 bg-slate-950/60 border-b border-slate-800 flex items-center justify-between cursor-pointer" @click="open = !open">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-cyber font-bold text-xs">
                        04
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">Bangladesh Trust & Perks Matrix</h3>
                        <p class="text-[10px] font-mono text-slate-400">4 trust cards (Delivery, bKash, Warranty, Rewards)</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3" @click.stop>
                    <span class="text-xs font-mono font-bold {{ ($perks?->is_active ?? true) ? 'text-emerald-400' : 'text-slate-500' }}">
                        {{ ($perks?->is_active ?? true) ? '● VISIBLE' : '○ HIDDEN' }}
                    </span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </div>
            </div>

            <div x-show="open" class="p-6 sm:p-8 space-y-6">
                <form action="{{ route('admin.theme.update_section', 'trust_badges') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <label class="flex items-center space-x-2 text-xs font-mono text-emerald-300 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ ($perks?->is_active ?? true) ? 'checked' : '' }} class="rounded text-emerald-500">
                        <span class="font-bold">Show Trust & Perks Cards</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                        <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                            <label class="text-cyan-400 font-bold">Perk 1 (Fast BD Delivery)</label>
                            <input type="text" name="content[card1_title]" value="{{ $pkContent['card1_title'] ?? '' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                            <textarea name="content[card1_desc]" rows="2" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-300">{{ $pkContent['card1_desc'] ?? '' }}</textarea>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                            <label class="text-pink-400 font-bold">Perk 2 (bKash & COD)</label>
                            <input type="text" name="content[card2_title]" value="{{ $pkContent['card2_title'] ?? '' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                            <textarea name="content[card2_desc]" rows="2" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-300">{{ $pkContent['card2_desc'] ?? '' }}</textarea>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                            <label class="text-emerald-400 font-bold">Perk 3 (Warranty & Genuine)</label>
                            <input type="text" name="content[card3_title]" value="{{ $pkContent['card3_title'] ?? '' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                            <textarea name="content[card3_desc]" rows="2" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-300">{{ $pkContent['card3_desc'] ?? '' }}</textarea>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                            <label class="text-amber-400 font-bold">Perk 4 (Spin & Win)</label>
                            <input type="text" name="content[card4_title]" value="{{ $pkContent['card4_title'] ?? '' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                            <textarea name="content[card4_desc]" rows="2" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-300">{{ $pkContent['card4_desc'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                        Save Trust Perks 💾
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- TAB 2: GLOBAL THEME, BRANDING & TICKERS -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'global'" x-cloak class="space-y-6">
        <form action="{{ route('admin.theme.update_settings') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Card 1: Branding & Identity -->
            <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-5">
                <h3 class="font-cyber font-bold text-sm text-cyan-400 uppercase tracking-wider">
                    Store Branding & Visual Identity
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Site Logo Name</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'NEXUS DOKAN' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-slate-300">Site Tagline (Under Logo)</label>
                        <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? 'NEXT-GEN ECOMMERCE BD' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>
                </div>

                <!-- Color Palette Selectors -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-mono pt-2">
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                        <label class="text-slate-300">Primary Neon Color</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" name="primary_neon_color" value="{{ $settings['primary_neon_color'] ?? '#00f2fe' }}" class="w-9 h-9 rounded-lg bg-transparent border-0 cursor-pointer">
                            <input type="text" value="{{ $settings['primary_neon_color'] ?? '#00f2fe' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2 py-1.5 text-white">
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                        <label class="text-slate-300">Secondary Neon Glow</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" name="secondary_neon_color" value="{{ $settings['secondary_neon_color'] ?? '#ff007f' }}" class="w-9 h-9 rounded-lg bg-transparent border-0 cursor-pointer">
                            <input type="text" value="{{ $settings['secondary_neon_color'] ?? '#ff007f' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2 py-1.5 text-white">
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                        <label class="text-slate-300">Dark Background Tone</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" name="bg_dark_color" value="{{ $settings['bg_dark_color'] ?? '#07080e' }}" class="w-9 h-9 rounded-lg bg-transparent border-0 cursor-pointer">
                            <input type="text" value="{{ $settings['bg_dark_color'] ?? '#07080e' }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2 py-1.5 text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Announcement Ticker & Thresholds -->
            <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-5">
                <h3 class="font-cyber font-bold text-sm text-pink-400 uppercase tracking-wider">
                    Header Announcement Marquee & BD Logistics
                </h3>

                <div class="space-y-3 text-xs font-mono">
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Ticker Line 1 (Flash Sale Notice)</label>
                        <input type="text" name="ticker_text_1" value="{{ $settings['ticker_text_1'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-slate-300">Ticker Line 2 (Free Shipping Code Notice)</label>
                        <input type="text" name="ticker_text_2" value="{{ $settings['ticker_text_2'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-slate-300">Free Delivery Minimum Order (৳ BDT)</label>
                            <input type="number" name="free_shipping_threshold" value="{{ $settings['free_shipping_threshold'] ?? '2000' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-slate-300">Customer Support Hotline</label>
                            <input type="text" name="hotline_phone" value="{{ $settings['hotline_phone'] ?? '+880 1711-000111' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Interactive Features Toggle -->
            <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-5">
                <h3 class="font-cyber font-bold text-sm text-emerald-400 uppercase tracking-wider">
                    Interactive Gamification & AI Features
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-mono">
                    <label class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 flex items-center justify-between cursor-pointer">
                        <span>Lucky Cyber Spin</span>
                        <input type="checkbox" name="enable_lucky_wheel" value="1" {{ ($settings['enable_lucky_wheel'] ?? '1') == '1' ? 'checked' : '' }} class="rounded text-amber-500 w-4 h-4">
                    </label>

                    <label class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 flex items-center justify-between cursor-pointer">
                        <span>Aura AI Shopping Bot</span>
                        <input type="checkbox" name="enable_ai_assistant" value="1" {{ ($settings['enable_ai_assistant'] ?? '1') == '1' ? 'checked' : '' }} class="rounded text-cyan-500 w-4 h-4">
                    </label>

                    <label class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 flex items-center justify-between cursor-pointer">
                        <span>Social Proof Toasts</span>
                        <input type="checkbox" name="enable_social_proof" value="1" {{ ($settings['enable_social_proof'] ?? '1') == '1' ? 'checked' : '' }} class="rounded text-pink-500 w-4 h-4">
                    </label>
                </div>
            </div>

            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-pink hover:scale-105 transition-all">
                Publish Global Theme Settings 🚀
            </button>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function themeBuilder() {
        return {
            activeTab: 'sections'
        }
    }
</script>
@endpush
