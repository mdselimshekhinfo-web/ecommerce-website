<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $siteSettings = \App\Models\ThemeSetting::pluck('value', 'key')->toArray();
        $logoType = $siteSettings['logo_type'] ?? 'text';
        $siteName = $siteSettings['site_name'] ?? 'NEXUS DOKAN';
        $siteTagline = $siteSettings['site_tagline'] ?? 'NEXT-GEN ECOMMERCE BD';
        $logoIcon = $siteSettings['logo_icon'] ?? 'cpu';
        $logoImageUrl = $siteSettings['logo_image_url'] ?? '';
        $faviconUrl = $siteSettings['favicon_url'] ?? '';
        $primaryNeon = $siteSettings['primary_neon_color'] ?? '#00f2fe';
        $secondaryNeon = $siteSettings['secondary_neon_color'] ?? '#ff007f';
        $bgDark = $siteSettings['bg_dark_color'] ?? '#07080e';
        $enableLuckyWheel = ($siteSettings['enable_lucky_wheel'] ?? '1') == '1';
        $enableAiAssistant = ($siteSettings['enable_ai_assistant'] ?? '1') == '1';
        $enableSocialProof = ($siteSettings['enable_social_proof'] ?? '1') == '1';
        $ticker1 = $siteSettings['ticker_text_1'] ?? '⚡ FLASH SALE: Up to 50% OFF on Neural Wearables & Mechanical Peripherals!';
        $ticker2 = $siteSettings['ticker_text_2'] ?? '🇧🇩 Free Shipping Anywhere in Bangladesh on orders ৳2,000+ (Code: FREESHIPBD)';
        $ticker3 = $siteSettings['ticker_text_3'] ?? '📱 bKash & Nagad Direct Seamless Instant Checkout';
        $hotline = $siteSettings['hotline_phone'] ?? '+880 1711-000111';
        $supportEmail = $siteSettings['support_email'] ?? 'support@nexusdokan.bd';
        $storeAddress = $siteSettings['store_address'] ?? 'Level 6, Cyber Hub, Gulshan-2, Dhaka-1212';
        $aboutText = $siteSettings['store_about_text'] ?? 'Specializing in high-performance cyber wearables, ANC audio gear, mechanical battlestation setups, and fast express delivery across all 64 districts.';
        $vatBin = $siteSettings['vat_bin_number'] ?? 'BIN: 00491823-0101 (VAT Registered)';
        $whatsapp = $siteSettings['whatsapp_number'] ?? '+8801711000111';
    @endphp

    <title>@yield('title', $siteName . ' // Futuristic Cyber eCommerce Bangladesh')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Orbitron:wght@600;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Hind Siliguri"', 'sans-serif'],
                        cyber: ['"Orbitron"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                        bn: ['"Hind Siliguri"', 'sans-serif'],
                    },
                    colors: {
                        cyber: {
                            bg: '{{ $bgDark }}',
                            card: '#0e111d',
                            glass: 'rgba(18, 22, 38, 0.75)',
                            border: 'rgba(0, 242, 254, 0.15)',
                            neon: '{{ $primaryNeon }}',
                            pink: '{{ $secondaryNeon }}',
                            purple: '#7928ca',
                            green: '#00ff88',
                            gold: '#fbbf24',
                            bkash: '#e2136e',
                            nagad: '#f7941d',
                        }
                    },
                    boxShadow: {
                        'neon-cyan': '0 0 20px rgba(0, 242, 254, 0.35)',
                        'neon-pink': '0 0 20px rgba(255, 0, 127, 0.35)',
                        'neon-green': '0 0 20px rgba(0, 255, 136, 0.35)',
                        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
                    },
                    animation: {
                        'pulse-glow': 'pulseGlow 2.5s infinite',
                        'float': 'float 4s ease-in-out infinite',
                        'marquee': 'marquee 25s linear infinite',
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%, 100%': { opacity: 0.8, filter: 'drop-shadow(0 0 15px {{ $primaryNeon }})' },
                            '50%': { opacity: 1, filter: 'drop-shadow(0 0 30px {{ $secondaryNeon }})' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-8px)' },
                        },
                        marquee: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-50%)' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        body {
            background-color: #080c14;
            background-image: 
                radial-gradient(circle at 10% 10%, rgba(6, 182, 212, 0.03) 0%, transparent 40%),
                radial-gradient(circle at 90% 90%, rgba(99, 102, 241, 0.04) 0%, transparent 40%);
            background-attachment: fixed;
            color: #f1f5f9;
        }

        .glass-panel {
            background: rgba(13, 18, 30, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: rgba(6, 182, 212, 0.35);
            background: rgba(18, 26, 48, 0.8);
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.6);
        }

        .cyber-btn {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            color: #040814;
            font-weight: 700;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .cyber-btn:hover {
            box-shadow: 0 4px 20px -2px rgba(6, 182, 212, 0.4);
            transform: translateY(-1px);
        }

        [x-cloak] { display: none !important; }
    </style>
    {!! \App\Helpers\PixelHelper::renderHeaderTags() !!}
    @stack('styles')
</head>
<body class="font-sans antialiased min-h-screen flex flex-col selection:bg-cyan-500 selection:text-black"
      x-data="globalApp()" x-init="initApp()">
    {!! \App\Helpers\PixelHelper::renderBodyTags() !!}

    <!-- 1. Top Dynamic Announcement Ticker -->
    <div class="relative z-50 bg-gradient-to-r from-purple-950 via-slate-900 to-cyan-950 border-b border-cyan-500/20 text-xs py-2 overflow-hidden select-none">
        <div class="flex items-center space-x-8 whitespace-nowrap animate-marquee">
            <span class="inline-flex items-center text-cyan-300 font-semibold">
                <i data-lucide="zap" class="w-3.5 h-3.5 mr-1 text-yellow-400"></i> {{ $ticker1 }}
            </span>
            <span class="text-slate-400">•</span>
            <span class="inline-flex items-center text-emerald-300 font-medium">
                <i data-lucide="truck" class="w-3.5 h-3.5 mr-1 text-emerald-400"></i> {{ $ticker2 }}
            </span>
            <span class="text-slate-400">•</span>
            <span class="inline-flex items-center text-pink-400 font-medium">
                <i data-lucide="smartphone" class="w-3.5 h-3.5 mr-1 text-pink-400"></i> {{ $ticker3 }}
            </span>
            <!-- Duplicate for infinite seamless scroll -->
            <span class="inline-flex items-center text-cyan-300 font-semibold">
                <i data-lucide="zap" class="w-3.5 h-3.5 mr-1 text-yellow-400"></i> {{ $ticker1 }}
            </span>
            <span class="text-slate-400">•</span>
            <span class="inline-flex items-center text-emerald-300 font-medium">
                <i data-lucide="truck" class="w-3.5 h-3.5 mr-1 text-emerald-400"></i> {{ $ticker2 }}
            </span>
        </div>
    </div>

    <!-- 2. Main Navigation Header -->
    <header class="sticky top-0 z-40 glass-panel border-b border-white/10 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Dynamic Brand / Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    @if($logoType === 'image' && !empty($logoImageUrl))
                        <img src="{{ $logoImageUrl }}" alt="{{ $siteName }}" class="h-10 max-w-[200px] object-contain group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="relative w-11 h-11 rounded-xl bg-gradient-to-tr from-cyan-500 via-indigo-600 to-pink-500 p-0.5 shadow-neon-cyan group-hover:scale-105 transition-transform duration-300">
                            <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center">
                                <i data-lucide="{{ $logoIcon ?: 'cpu' }}" class="w-6 h-6 text-cyan-400 group-hover:rotate-12 transition-transform"></i>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-1.5">
                                <span class="font-cyber font-black text-2xl tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-200 to-pink-400">
                                    {{ $siteName }}
                                </span>
                            </div>
                            <p class="text-[10px] font-mono tracking-widest text-slate-400 uppercase -mt-0.5">
                                {{ $siteTagline }}
                            </p>
                        </div>
                    @endif
                   <!-- Nav Links -->
                <nav class="hidden md:flex items-center space-x-1 font-medium text-sm">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-white/5 transition-all {{ request()->routeIs('home') ? 'text-cyan-400 bg-cyan-500/10 font-semibold' : '' }}">
                        <span>{{ \App\Helpers\LocalizationHelper::get('nav_home') }}</span>
                    </a>
                    <a href="{{ route('shop.index') }}" class="px-4 py-2 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-white/5 transition-all {{ request()->routeIs('shop.index') && !request('flash_deals') ? 'text-cyan-400 bg-cyan-500/10 font-semibold' : '' }}">
                        <span>{{ \App\Helpers\LocalizationHelper::get('nav_shop') }}</span>
                    </a>

                    <!-- Categories Megamenu Dropdown -->
                    @php $navCategories = \App\Models\Category::where('status','active')->orderBy('name')->take(10)->get(); @endphp
                    <div class="relative" x-data="{ catOpen: false }" @mouseenter="catOpen=true" @mouseleave="catOpen=false">
                        <button class="flex items-center space-x-1 px-4 py-2 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-white/5 transition-all">
                            <span>{{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'ক্যাটাগরি' : 'Categories' }}</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform" :class="catOpen ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="catOpen" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                             class="absolute left-0 top-full mt-1 w-64 bg-slate-900/98 border border-cyan-500/20 rounded-2xl shadow-2xl p-3 z-50 backdrop-blur-xl">
                            <div class="space-y-0.5">
                                <a href="{{ route('shop.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs text-slate-300 hover:text-cyan-300 hover:bg-cyan-500/10 transition-all font-medium">
                                    <i data-lucide="grid" class="w-3.5 h-3.5 text-cyan-400"></i>
                                    <span>{{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'সব পণ্য' : 'All Products' }}</span>
                                </a>
                                @foreach($navCategories as $nc)
                                <a href="{{ route('shop.index', ['category' => $nc->slug]) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs text-slate-400 hover:text-cyan-300 hover:bg-cyan-500/8 transition-all">
                                    <span class="flex items-center space-x-2.5">
                                        <i data-lucide="tag" class="w-3.5 h-3.5 text-slate-600"></i>
                                        <span>{{ $nc->name }}</span>
                                    </span>
                                    <span class="text-[10px] font-mono bg-slate-800 px-1.5 py-0.5 rounded text-slate-500">{{ $nc->products_count ?? '' }}</span>
                                </a>
                                @endforeach
                            </div>
                            <div class="mt-2 pt-2 border-t border-slate-800">
                                <a href="{{ route('shop.index', ['flash_deals' => 1]) }}" class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs text-pink-400 hover:bg-pink-500/10 font-semibold transition-all">
                                    <i data-lucide="flame" class="w-3.5 h-3.5 animate-pulse"></i>
                                    <span>Flash Deals</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('shop.index', ['flash_deals' => 1]) }}" class="px-4 py-2 rounded-lg text-pink-400 hover:text-pink-300 hover:bg-pink-500/10 transition-all flex items-center space-x-1.5 {{ request('flash_deals') ? 'bg-pink-500/10 font-semibold' : '' }}">
                        <i data-lucide="flame" class="w-4 h-4 text-pink-400 animate-bounce"></i>
                        <span>{{ \App\Helpers\LocalizationHelper::get('nav_deals') }}</span>
                    </a>
                    <a href="{{ route('order.track') }}" class="px-4 py-2 rounded-lg text-slate-300 hover:text-cyan-400 hover:bg-white/5 transition-all {{ request()->routeIs('order.track') ? 'text-cyan-400 bg-cyan-500/10 font-semibold' : '' }}">
                        <span>{{ \App\Helpers\LocalizationHelper::get('nav_track') }}</span>
                    </a>
                </nav>

                <!-- Search Input with Live Dropdown -->
                <div class="hidden lg:block relative w-72" x-data="liveSearch()">
                    <div class="relative">
                        <input type="text" 
                               x-model="query" 
                               @input.debounce.300ms="search()" 
                               @focus="open = true" 
                               @click.away="open = false" 
                               placeholder="{{ \App\Helpers\LocalizationHelper::get('search_placeholder') }}" 
                               class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl pl-10 pr-4 py-2 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-all shadow-inner">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5"></i>
                        <div x-show="loading" class="absolute right-3 top-2.5">
                            <i data-lucide="loader" class="w-4 h-4 text-cyan-400 animate-spin"></i>
                        </div>
                    </div>

                    <!-- Dropdown Results -->
                    <div x-show="open && results.length > 0" x-cloak
                         class="absolute left-0 right-0 mt-2 bg-slate-900/95 border border-cyan-500/30 rounded-xl shadow-2xl p-2 z-50 backdrop-blur-xl max-h-96 overflow-y-auto">
                        <template x-for="item in results" :key="item.id">
                            <a :href="'/product/' + item.slug" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-cyan-500/10 transition-colors group">
                                <img :src="item.thumbnail" class="w-10 h-10 object-cover rounded-lg border border-slate-700">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-semibold text-white group-hover:text-cyan-300 truncate" x-text="item.name"></h4>
                                    <div class="flex items-center space-x-2 text-[11px]">
                                        <span class="text-cyan-400 font-bold" x-text="'৳' + item.sale_price || item.price"></span>
                                        <span x-show="item.badge" class="text-[9px] px-1 rounded bg-pink-500/20 text-pink-300 font-mono" x-text="item.badge"></span>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- Right Utility Icons & Controls -->
                <div class="flex items-center space-x-2.5">

                    <!-- Sleek Minimalist 1-Click Language Switcher (Compact Pill) -->
                    @php
                        $isStoreBn = \App\Helpers\LocalizationHelper::getLocale() === 'bn';
                    @endphp
                    <a href="{{ route('language.toggle') }}" 
                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-900 border border-slate-700/80 hover:border-cyan-400/60 text-[11px] font-mono font-bold transition-all shadow-sm group select-none cursor-pointer"
                       title="{{ $isStoreBn ? 'Switch to English' : 'বাংলায় পরিবর্তন করুন' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isStoreBn ? 'bg-emerald-400 animate-pulse' : 'bg-cyan-400' }}"></span>
                        <span class="text-slate-300 group-hover:text-white">{{ $isStoreBn ? '🇧🇩 বাংলা' : '🇬🇧 EN' }}</span>
                        <i data-lucide="arrow-left-right" class="w-3 h-3 text-slate-500 group-hover:text-cyan-400 transition-colors"></i>
                    </a>

                    <!-- Cart Drawer Trigger Button -->
                    <button @click="openCartDrawer()" class="relative p-2.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-cyan-400 text-slate-300 hover:text-cyan-300 transition-all shadow-inner group">
                        <i data-lucide="shopping-bag" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                        <span x-show="cartCount > 0" x-text="cartCount" 
                              class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-gradient-to-r from-cyan-400 to-pink-500 text-slate-950 font-black text-[10px] flex items-center justify-center shadow-neon-cyan animate-pulse">
                        </span>
                    </button>

                    <!-- User Account / Auth Dropdown -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 p-1.5 rounded-xl border border-slate-700 hover:border-cyan-400/50 bg-slate-900/80 transition-all">
                                <img src="{{ Auth::user()->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" 
                                     class="w-7 h-7 rounded-lg object-cover border border-cyan-400/30">
                                <span class="hidden md:inline-block text-xs font-semibold text-slate-200 truncate max-w-[90px]">{{ Auth::user()->name }}</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                            </button>

                            <div x-show="open" x-cloak class="absolute right-0 mt-2 w-52 bg-slate-900 border border-cyan-500/30 rounded-xl shadow-2xl p-2 z-50 backdrop-blur-xl">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 p-2 rounded-lg text-xs text-cyan-400 hover:bg-cyan-500/10 font-semibold">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                        <span>Admin Command Center</span>
                                    </a>
                                    <a href="{{ route('admin.theme.index') }}" class="flex items-center space-x-2.5 p-2 rounded-lg text-xs text-pink-400 hover:bg-pink-500/10 font-semibold">
                                        <i data-lucide="palette" class="w-4 h-4"></i>
                                        <span>Theme & Section Builder</span>
                                    </a>
                                @endif
                                <a href="{{ route('customer.dashboard') }}" class="flex items-center space-x-2.5 p-2 rounded-lg text-xs text-slate-300 hover:bg-white/5 font-medium">
                                    <i data-lucide="user" class="w-4 h-4 text-cyan-400"></i>
                                    <span>My Cyber Portal</span>
                                </a>
                                <a href="{{ route('order.track') }}" class="flex items-center space-x-2.5 p-2 rounded-lg text-xs text-slate-300 hover:bg-white/5 font-medium">
                                    <i data-lucide="package" class="w-4 h-4 text-indigo-400"></i>
                                    <span>Track Orders</span>
                                </a>
                                <div class="border-t border-slate-800 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center space-x-2.5 p-2 rounded-lg text-xs text-red-400 hover:bg-red-500/10 font-semibold text-left">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        <span>Log Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg border border-slate-700 hover:border-cyan-400 bg-slate-900/80 text-xs font-semibold text-slate-300 hover:text-white transition-all">
                                <span x-text="lang === 'bn' ? 'লগইন' : 'Login'">Login</span>
                            </a>
                            <a href="{{ route('quick.login', 'customer') }}" class="hidden sm:inline-flex px-3 py-1.5 rounded-lg bg-cyan-500/10 border border-cyan-500/40 text-cyan-300 hover:bg-cyan-500/20 text-xs font-semibold font-mono transition-all" title="Instant Demo Login">
                                Demo Login ⚡
                            </a>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </header>

    <!-- 📱 Mobile Bottom Navigation Bar (Only on mobile/tablet) -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 md:hidden bg-slate-950/95 border-t border-slate-800 backdrop-blur-xl">
        <div class="flex items-center justify-around px-1 py-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center space-y-1 px-3 py-1 rounded-xl {{ request()->routeIs('home') ? 'text-cyan-400' : 'text-slate-500' }} hover:text-cyan-400 transition-colors">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold uppercase">Home</span>
            </a>
            <a href="{{ route('shop.index') }}" class="flex flex-col items-center space-y-1 px-3 py-1 rounded-xl {{ request()->routeIs('shop.*') ? 'text-cyan-400' : 'text-slate-500' }} hover:text-cyan-400 transition-colors">
                <i data-lucide="grid-3x3" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold uppercase">Shop</span>
            </a>
            <button @click="openCartDrawer()" class="relative flex flex-col items-center space-y-1 px-3 py-1 rounded-xl text-slate-500 hover:text-cyan-400 transition-colors">
                <span class="relative">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span x-show="cartCount > 0" x-text="cartCount"
                          class="absolute -top-2 -right-2 w-4 h-4 rounded-full bg-cyan-400 text-slate-950 font-black text-[9px] flex items-center justify-center animate-pulse"></span>
                </span>
                <span class="text-[9px] font-bold uppercase">Cart</span>
            </button>
            <a href="{{ route('order.track') }}" class="flex flex-col items-center space-y-1 px-3 py-1 rounded-xl {{ request()->routeIs('order.track') ? 'text-pink-400' : 'text-slate-500' }} hover:text-pink-400 transition-colors">
                <i data-lucide="package-search" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold uppercase">Track</span>
            </a>
            @auth
            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center space-y-1 px-3 py-1 rounded-xl {{ request()->routeIs('customer.*') ? 'text-emerald-400' : 'text-slate-500' }} hover:text-emerald-400 transition-colors">
                <i data-lucide="user-circle" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold uppercase">Account</span>
            </a>
            @else
            <a href="{{ route('login') }}" class="flex flex-col items-center space-y-1 px-3 py-1 rounded-xl text-slate-500 hover:text-cyan-400 transition-colors">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold uppercase">Login</span>
            </a>
            @endauth
        </div>
    </nav>

    <!-- 🔼 Scroll To Top Button -->
    <button x-show="showScrollTop" x-cloak @click="window.scrollTo({top:0,behavior:'smooth'})"
            class="fixed bottom-20 right-5 md:bottom-8 z-40 w-11 h-11 rounded-full bg-slate-900 border border-slate-700 hover:border-cyan-400 text-slate-400 hover:text-cyan-300 shadow-lg transition-all hover:scale-110 flex items-center justify-center"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
        <i data-lucide="chevron-up" class="w-5 h-5"></i>
    </button>

    <!-- Main Content -->
    <main class="flex-1 relative z-10 pb-16 md:pb-0">
        @if(session('success'))
            <div class="max-w-4xl mx-auto px-4 mt-4">
                <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 text-sm flex items-center justify-between shadow-neon-green">
                    <div class="flex items-center space-x-2.5">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400 shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="$el.parentElement.remove()" class="text-emerald-400 hover:text-white">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-4xl mx-auto px-4 mt-4">
                <div class="p-4 rounded-xl bg-red-950/80 border border-red-500/40 text-red-300 text-sm flex items-center justify-between shadow-neon-pink">
                    <div class="flex items-center space-x-2.5">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-400 shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="$el.parentElement.remove()" class="text-red-400 hover:text-white">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- 3. Dynamic Footer -->
    <footer class="mt-20 border-t border-slate-800 bg-slate-950/90 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        @if($logoType === 'image' && !empty($logoImageUrl))
                            <img src="{{ $logoImageUrl }}" alt="{{ $siteName }}" class="h-9 max-w-[180px] object-contain">
                        @else
                            <div class="w-9 h-9 rounded-lg bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center">
                                <i data-lucide="{{ $logoIcon ?: 'cpu' }}" class="w-5 h-5 text-cyan-400"></i>
                            </div>
                            <span class="font-cyber font-black text-xl tracking-wider text-white">{{ $siteName }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        {{ $aboutText }}
                    </p>
                    
                    <div class="space-y-2 text-xs text-slate-300 font-mono">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="phone" class="w-4 h-4 text-cyan-400"></i>
                            <span>{{ $hotline }} (24/7 Hotline)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i data-lucide="mail" class="w-4 h-4 text-pink-400"></i>
                            <span>{{ $supportEmail }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-emerald-400"></i>
                            <span>{{ $storeAddress }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-[11px] text-slate-400">
                            <i data-lucide="shield-check" class="w-4 h-4 text-amber-400"></i>
                            <span>{{ $vatBin }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <h4 class="font-cyber font-bold text-cyan-400 tracking-wider uppercase">Cyber Catalog</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="{{ route('shop.index', ['category' => 'cyber-audio-anc']) }}" class="hover:text-cyan-300 transition-colors">Cyber Audio & ANC</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'smart-wearables-neural']) }}" class="hover:text-cyan-300 transition-colors">Smart Wearables & Watches</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'quantum-peripherals']) }}" class="hover:text-cyan-300 transition-colors">Mechanical Keyboards & 8K Mice</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'cyberpunk-techwear']) }}" class="hover:text-cyan-300 transition-colors">Techwear Bags & Apparel</a></li>
                    </ul>
                </div>

                <div class="space-y-3 text-xs">
                    <h4 class="font-cyber font-bold text-pink-400 tracking-wider uppercase">পলিসি ও সহায়তা</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="{{ route('order.track') }}" class="hover:text-pink-300 transition-colors">Track Live Courier Status</a></li>
                        @php
                            $footerPages = \App\Models\CustomPage::where('is_footer_link', true)->where('status', 'published')->get();
                        @endphp
                        @foreach($footerPages as $fPage)
                            <li><a href="{{ route('page.show', $fPage->slug) }}" class="hover:text-cyan-300 transition-colors">{{ $fPage->title }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="space-y-4 text-xs">
                    <h4 class="font-cyber font-bold text-amber-400 tracking-wider uppercase">Admin Control</h4>
                    <div class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="block w-full py-2 px-3 rounded-lg bg-purple-950/60 border border-purple-500/40 text-purple-300 hover:bg-purple-900/60 font-mono text-center font-semibold transition-all">
                            Admin Command Center ⚡
                        </a>
                        <a href="{{ route('admin.theme.index') }}" class="block w-full py-2 px-3 rounded-lg bg-pink-950/60 border border-pink-500/40 text-pink-300 hover:bg-pink-900/60 font-mono text-center font-semibold transition-all">
                            Theme & Section Builder 🎨
                        </a>
                    </div>
                </div>

            </div>

            <!-- Payment Partners & Badges -->
            <div class="mt-12 pt-6 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center flex-wrap gap-2 text-xs text-slate-400">
                    <span>Verified BD Payments:</span>
                    <span class="px-2.5 py-1 rounded bg-[#e2136e]/20 border border-[#e2136e]/40 text-[#ff4b98] font-bold">bKash</span>
                    <span class="px-2.5 py-1 rounded bg-[#f7941d]/20 border border-[#f7941d]/40 text-[#ffa940] font-bold">Nagad</span>
                    <span class="px-2.5 py-1 rounded bg-purple-900/30 border border-purple-500/40 text-purple-300 font-bold">Rocket</span>
                    <span class="px-2.5 py-1 rounded bg-emerald-900/30 border border-emerald-500/40 text-emerald-300 font-bold">Cash On Delivery</span>
                    <span class="px-2.5 py-1 rounded bg-blue-900/30 border border-blue-500/40 text-blue-300 font-bold">Visa/Mastercard</span>
                </div>
                <p class="text-xs text-slate-500 font-mono">
                    &copy; {{ date('Y') }} {{ $siteName }} BD. Crafted with futuristic passion.
                </p>
            </div>
        </div>
    </footer>

    <!-- 4. Interactive Slide-over Cyber Cart Drawer -->
    <div x-show="cartDrawerOpen" x-cloak class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div x-show="cartDrawerOpen" 
             x-transition:enter="ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="cartDrawerOpen = false"
             class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="cartDrawerOpen"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-slate-900 border-l border-cyan-500/30 shadow-2xl flex flex-col">
                
                <div class="p-5 border-b border-slate-800 flex items-center justify-between bg-slate-950/60">
                    <div class="flex items-center space-x-2.5">
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-cyan-400"></i>
                        <h3 class="font-cyber font-bold text-white text-base tracking-wide">CYBER CART</h3>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 font-mono" x-text="cartCount + ' Items'"></span>
                    </div>
                    <button @click="cartDrawerOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="p-4 bg-slate-950/40 border-b border-slate-800/80">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-slate-300 flex items-center">
                            <i data-lucide="truck" class="w-3.5 h-3.5 text-emerald-400 mr-1.5"></i>
                            <span x-text="rawSubtotal >= 2000 ? '🎉 Free Delivery Unlocked across BD!' : 'Add ৳' + Math.max(0, 2000 - rawSubtotal) + ' more for FREE Delivery!'"></span>
                        </span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-cyan-400 to-emerald-400 transition-all duration-500"
                             :style="'width: ' + Math.min(100, (rawSubtotal / 2000) * 100) + '%'"></div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-4">
                    <template x-if="Object.keys(cartItems).length === 0">
                        <div class="text-center py-16 space-y-4">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-800/50 flex items-center justify-center border border-slate-700">
                                <i data-lucide="shopping-cart" class="w-8 h-8 text-slate-500"></i>
                            </div>
                            <h4 class="text-slate-300 font-semibold text-sm">Your Cyber Cart is Empty</h4>
                            <p class="text-xs text-slate-500">Explore our futuristic gear to power up your lifestyle.</p>
                            <a href="{{ route('shop.index') }}" @click="cartDrawerOpen = false" class="inline-block px-5 py-2.5 rounded-xl cyber-btn text-xs font-bold shadow-neon-cyan">
                                Start Shopping
                            </a>
                        </div>
                    </template>

                    <template x-for="(item, key) in cartItems" :key="key">
                        <div class="flex items-center space-x-3.5 p-3 rounded-xl bg-slate-950/60 border border-slate-800 hover:border-cyan-500/30 transition-all group">
                            <img :src="item.thumbnail" class="w-16 h-16 object-cover rounded-lg border border-slate-700 shrink-0">
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-semibold text-white truncate" x-text="item.name"></h4>
                                <p class="text-[10px] text-cyan-400 font-mono mt-0.5" x-text="item.variant"></p>
                                
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center space-x-1.5 bg-slate-900 border border-slate-700 rounded-lg px-2 py-0.5">
                                        <button @click="updateDrawerQty(key, item.quantity - 1)" class="text-slate-400 hover:text-white text-xs px-1">-</button>
                                        <span class="text-xs font-mono font-bold text-white px-1" x-text="item.quantity"></span>
                                        <button @click="updateDrawerQty(key, item.quantity + 1)" class="text-slate-400 hover:text-white text-xs px-1">+</button>
                                    </div>
                                    <span class="text-xs font-mono font-bold text-cyan-300" x-text="'৳' + (item.price * item.quantity).toLocaleString()"></span>
                                </div>
                            </div>

                            <button @click="removeFromDrawer(key)" class="text-slate-500 hover:text-red-400 p-1.5 rounded-lg hover:bg-red-500/10 transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="p-5 border-t border-slate-800 bg-slate-950/80 space-y-3" x-show="Object.keys(cartItems).length > 0">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">Subtotal:</span>
                        <span class="font-mono font-bold text-lg text-white" x-text="cartSubtotal"></span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="block w-full py-3.5 rounded-xl cyber-btn text-center text-xs uppercase font-extrabold tracking-wider shadow-neon-cyan">
                        Proceed to Checkout 🚀
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- 5. Floating Interactive Lucky Spin Widget (Bottom-Left) -->
    @if($enableLuckyWheel)
    <div class="fixed bottom-6 left-6 z-40 select-none">
        <button @click="spinModal = true" 
                class="group relative flex items-center space-x-2.5 pl-2.5 pr-4 py-2 rounded-full bg-slate-950/90 border border-amber-500/40 hover:border-amber-400 backdrop-blur-xl shadow-2xl hover:scale-105 transition-all duration-300">
            <div class="relative w-8 h-8 rounded-full bg-gradient-to-tr from-amber-400 via-orange-500 to-pink-500 flex items-center justify-center shadow-md">
                <i data-lucide="gift" class="w-4 h-4 text-slate-950 animate-bounce"></i>
                <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
            </div>
            <div class="text-left">
                <span class="block text-[9px] font-mono font-bold text-amber-400 uppercase tracking-widest leading-none">
                    {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'লাকি স্পিন অফার' : 'LUCKY SPIN BD' }}
                </span>
                <span class="block text-xs font-black text-white leading-tight">
                    {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? '🎁 স্পিন ও ভাউচার জিতুন' : '🎁 Spin & Win ৳500' }}
                </span>
            </div>
        </button>
    </div>

    <!-- Professional Gamified Lucky Wheel Modal -->
    <div x-show="spinModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/85 backdrop-blur-md" @click="spinModal = false"></div>

        <div class="relative w-full max-w-md bg-slate-900 border border-amber-500/40 rounded-3xl shadow-2xl p-6 sm:p-7 overflow-hidden z-10 text-center">
            
            <button @click="spinModal = false" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-white rounded-lg bg-white/5 hover:bg-white/10 transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <!-- Header -->
            <div class="mb-4">
                <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 text-xs font-mono font-bold mb-2">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span>{{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'লাকি ফরচুন হুইল' : 'CYBER FORTUNE WHEEL' }}</span>
                </div>
                <h3 class="font-cyber font-black text-xl sm:text-2xl text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-orange-400 to-pink-500">
                    {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'স্পিন করুন ও ভাউচার জিতুন' : 'SPIN & WIN DISCOUNTS' }}
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                    {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'প্রতিবার স্পিন করে জিতে নিন ৳৫০০ পর্যন্ত ইনস্ট্যান্ট ক্যাশব্যাক বা ফ্রি ডেলিভারি!' : 'Spin now to unlock instant discount coupons up to ৳500 or Free Shipping!' }}
                </p>
            </div>

            <!-- Precision SVG Segmented Wheel -->
            <div class="relative w-64 h-64 sm:w-72 sm:h-72 mx-auto my-5">
                <!-- Top Indicator Needle -->
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 z-20 w-0 h-0 border-l-[12px] border-l-transparent border-r-[12px] border-r-transparent border-t-[24px] border-t-amber-400 filter drop-shadow(0 4px 10px rgba(251, 191, 36, 0.9))"></div>
                
                <!-- Rotating SVG Wheel Container -->
                <div id="cyberWheel" class="w-full h-full rounded-full border-4 border-amber-500/50 shadow-2xl transition-transform duration-[4500ms] ease-out relative overflow-hidden"
                     style="background: #0f172a;">
                    <svg viewBox="0 0 300 300" class="w-full h-full">
                        <!-- 6 Slices: 60 deg each -->
                        <!-- Slice 0: 0 - 60 deg -->
                        <path d="M150,150 L150,0 A150,150 0 0,1 279.9,75 Z" fill="#1e1b4b" stroke="#312e81" stroke-width="2"/>
                        <!-- Slice 1: 60 - 120 deg -->
                        <path d="M150,150 L279.9,75 A150,150 0 0,1 279.9,225 Z" fill="#042f2e" stroke="#065f46" stroke-width="2"/>
                        <!-- Slice 2: 120 - 180 deg -->
                        <path d="M150,150 L279.9,225 A150,150 0 0,1 150,300 Z" fill="#312e81" stroke="#4338ca" stroke-width="2"/>
                        <!-- Slice 3: 180 - 240 deg -->
                        <path d="M150,150 L150,300 A150,150 0 0,1 20.1,225 Z" fill="#431407" stroke="#7c2d12" stroke-width="2"/>
                        <!-- Slice 4: 240 - 300 deg -->
                        <path d="M150,150 L20.1,225 A150,150 0 0,1 20.1,75 Z" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                        <!-- Slice 5: 300 - 360 deg -->
                        <path d="M150,150 L20.1,75 A150,150 0 0,1 150,0 Z" fill="#581c87" stroke="#6b21a8" stroke-width="2"/>

                        <!-- Slice Texts -->
                        <text x="195" y="55" fill="#38bdf8" font-size="12" font-weight="bold" font-family="JetBrains Mono" transform="rotate(30, 150, 150)">৳100 OFF</text>
                        <text x="210" y="55" fill="#f472b6" font-size="12" font-weight="bold" font-family="JetBrains Mono" transform="rotate(90, 150, 150)">10% OFF</text>
                        <text x="200" y="55" fill="#34d399" font-size="12" font-weight="bold" font-family="JetBrains Mono" transform="rotate(150, 150, 150)">FREE SHIP</text>
                        <text x="190" y="55" fill="#fbbf24" font-size="12" font-weight="bold" font-family="JetBrains Mono" transform="rotate(210, 150, 150)">৳500 MEGA</text>
                        <text x="205" y="55" fill="#a78bfa" font-size="12" font-weight="bold" font-family="JetBrains Mono" transform="rotate(270, 150, 150)">15% VIP</text>
                        <text x="195" y="55" fill="#fb7185" font-size="12" font-weight="bold" font-family="JetBrains Mono" transform="rotate(330, 150, 150)">৳200 OFF</text>
                    </svg>

                    <!-- Center Hub -->
                    <div class="absolute inset-0 m-auto w-16 h-16 rounded-full bg-slate-950 border-3 border-amber-400 flex items-center justify-center shadow-2xl z-10">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-amber-400 to-orange-500 flex items-center justify-center text-slate-950 shadow-inner">
                            <i data-lucide="sparkles" class="w-5 h-5 animate-pulse"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spin Result Box -->
            <div x-show="spinWon" x-cloak class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/40 text-amber-300 text-xs mb-4 animate-bounce">
                <p class="font-bold text-sm text-white" x-text="spinMessage"></p>
                <div class="mt-2.5 flex items-center justify-center space-x-2">
                    <span class="font-mono text-base font-black px-3.5 py-1.5 rounded-xl bg-black/80 border border-amber-400 text-amber-300 tracking-wider select-all" x-text="spinCoupon"></span>
                    <button @click="copyCoupon(spinCoupon)" class="px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold transition-all flex items-center space-x-1 shadow-md">
                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                        <span>{{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'কপি করুন' : 'Copy Code' }}</span>
                    </button>
                </div>
            </div>

            <!-- Spin Trigger Action -->
            <button @click="triggerSpin()" :disabled="spinning" 
                    class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-amber-400 via-orange-500 to-pink-500 text-slate-950 font-cyber font-black text-sm uppercase tracking-wider shadow-xl hover:scale-[1.02] transition-all disabled:opacity-50">
                <span x-text="spinning ? '⚡ {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'হুইল ঘুরছে...' : 'SPINNING WHEEL...' }}' : '⚡ {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'ফ্রি স্পিন করুন' : 'SPIN NOW FOR FREE' }}'"></span>
            </button>
        </div>
    </div>
    @endif

    <!-- 6. Floating Hybrid AI & Live Support Assistant Widget -->
    @if($enableAiAssistant)
    <div x-data="aiAssistant()" class="fixed bottom-6 right-6 z-40 select-none">
        <button @click="toggleChat()" class="relative group w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 via-indigo-600 to-pink-500 p-0.5 shadow-neon-cyan hover:scale-105 transition-all">
            <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                <i data-lucide="bot" class="w-7 h-7 text-cyan-400 group-hover:rotate-12 transition-transform"></i>
                <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-slate-900 animate-ping"></span>
                <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-slate-900"></span>
            </div>
        </button>

        <div x-show="chatOpen" x-cloak @click.away="chatOpen = false"
             class="absolute bottom-16 right-0 w-[330px] sm:w-[380px] bg-slate-950/95 border border-cyan-500/40 rounded-3xl shadow-2xl p-4 flex flex-col h-[520px] backdrop-blur-2xl z-50">
            
            <!-- Chat Top Header -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center text-cyan-400">
                        <i data-lucide="bot" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="font-cyber font-bold text-white text-xs flex items-center gap-1.5">
                            <span>AURA CYBER AI</span>
                            <span class="text-[9px] px-1.5 py-0.2 rounded bg-cyan-500/20 text-cyan-300 font-mono">HYBRID</span>
                        </h4>
                        <p class="text-[10px] text-emerald-400 font-mono flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1 animate-pulse"></span>
                            <span x-text="isHumanAssigned ? '👨‍💼 Live Human Support Connected' : '🤖 AI Auto-Pilot Sales Ready'"></span>
                        </p>
                    </div>
                </div>
                <button @click="chatOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/5 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Messages Stream Area -->
            <div class="flex-1 overflow-y-auto py-3 space-y-3 pr-1 text-xs font-mono" id="chatStream">
                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.sender_type === 'customer' ? 'flex justify-end' : 'flex justify-start'">
                        <div class="max-w-[85%] space-y-1">
                            
                            <!-- Sender Label -->
                            <div class="text-[9px] font-bold flex items-center gap-1"
                                 :class="msg.sender_type === 'customer' ? 'justify-end text-cyan-400' : (msg.sender_type === 'agent' ? 'text-emerald-400' : 'text-purple-400')">
                                <span x-text="msg.sender_type === 'customer' ? '👤 {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'আপনি' : 'You' }}' : (msg.sender_type === 'agent' ? '👨‍💼 {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'সাপোর্ট প্রতিনিধি' : 'Support Agent' }}' : '🤖 Aura AI (Auto-Pilot)')"></span>
                            </div>

                            <!-- Bubble Content -->
                            <div :class="msg.sender_type === 'customer' ? 'bg-cyan-500 text-slate-950 font-bold rounded-2xl rounded-tr-none' : (msg.sender_type === 'agent' ? 'bg-emerald-950 border border-emerald-500/40 text-emerald-200 rounded-2xl rounded-tl-none' : 'bg-slate-900 border border-cyan-500/30 text-slate-200 rounded-2xl rounded-tl-none')"
                                 class="px-3.5 py-2.5 text-xs leading-relaxed whitespace-pre-line shadow-md">
                                <span x-html="formatMessage(msg.message)"></span>

                                <!-- WhatsApp Button Payload -->
                                <template x-if="msg.message_type === 'whatsapp_redirect' && msg.payload">
                                    <div class="pt-2">
                                        <a :href="msg.payload.whatsapp_url" target="_blank" 
                                           class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-emerald-500 text-slate-950 font-bold text-[11px] shadow hover:bg-emerald-400 transition-all">
                                            <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                            <span x-text="msg.payload.button_text"></span>
                                        </a>
                                    </div>
                                </template>

                                <!-- Order Receipt Payload -->
                                <template x-if="msg.message_type === 'order_receipt' && msg.payload">
                                    <div class="mt-2 p-2.5 rounded-xl bg-emerald-900/60 border border-emerald-400/50 text-[11px] text-emerald-200 space-y-1">
                                        <div class="font-black flex justify-between items-center text-white">
                                            <span>✓ Order Confirmed</span>
                                            <span class="font-mono" x-text="'#' + msg.payload.order_number"></span>
                                        </div>
                                        <p class="text-[10px] text-slate-300" x-text="msg.payload.product_name"></p>
                                        <p class="text-[10px] text-emerald-300 font-bold" x-text="'Total: ৳' + Number(msg.payload.total_amount).toLocaleString()"></p>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>
                </template>

                <div x-show="botTyping" class="flex justify-start">
                    <div class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-slate-400 text-xs flex items-center space-x-1.5">
                        <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-pink-400 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                    </div>
                </div>
            </div>

            <!-- Preset Action Chips -->
            <div class="flex items-center space-x-1.5 overflow-x-auto py-2 border-t border-slate-800/80 no-scrollbar">
                <button @click="requestAgent()" class="text-[10px] px-2.5 py-1 rounded-full bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 whitespace-nowrap border border-emerald-500/40 font-bold transition-all">
                    👨‍💼 {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'সরাসরি এজেন্টের সাথে কথা বলুন' : 'Talk to Agent' }}
                </button>
                <button @click="askPreset('Delivery charges in BD?')" class="text-[10px] px-2 py-1 rounded-full bg-slate-900 hover:bg-cyan-500/20 text-slate-300 whitespace-nowrap border border-slate-800">
                    📦 {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'ডেলিভারি চার্জ' : 'Delivery Charge' }}
                </button>
                <button @click="askPreset('bKash payment policy?')" class="text-[10px] px-2 py-1 rounded-full bg-slate-900 hover:bg-pink-500/20 text-slate-300 whitespace-nowrap border border-slate-800">
                    💳 {{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'বিকাশ পেমেন্ট' : 'bKash Payment' }}
                </button>
            </div>

            <!-- Chat Input Field -->
            <form @submit.prevent="sendMessage()" class="mt-2 flex items-center space-x-2">
                <input type="text" x-model="userInput" 
                       :placeholder="isHumanAssigned ? '{{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'প্রতিনিধির সাথে কথা বলুন...' : 'Chat with live support agent...' }}' : '{{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'অর্ডার করতে নাম, ফোন ও ঠিকানা লিখুন...' : 'Type name, phone & address to order...' }}'" 
                       class="flex-1 bg-slate-900 border border-slate-700 rounded-2xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400">
                
                <button type="submit" class="p-2.5 rounded-2xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 transition-colors shadow-md">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- 7. Live Recent Purchase Toast (Compact Pill Size, Stacked Neatly Above Spin) -->
    @if($enableSocialProof)
    <div x-data="socialProof()" x-show="visible" x-cloak
         x-transition:enter="transform ease-out duration-300"
         x-transition:enter-start="translate-y-3 opacity-0 scale-90"
         x-transition:enter-end="translate-y-0 opacity-100 scale-100"
         x-transition:leave="transform ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100 scale-100"
         x-transition:leave-end="translate-y-3 opacity-0 scale-90"
         class="fixed bottom-[72px] left-6 z-40 bg-slate-950/95 border border-cyan-500/40 rounded-full pl-2 pr-3 py-1.5 shadow-2xl backdrop-blur-xl max-w-[250px] flex items-center space-x-2 select-none">
        
        <div class="relative shrink-0">
            <img :src="current.img" class="w-7 h-7 rounded-full object-cover border border-cyan-400/40">
            <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-400 border border-slate-950"></span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-[10px] font-bold text-cyan-300 truncate leading-none" x-text="current.name + ' • ' + current.location"></p>
            <p class="text-[9px] text-slate-200 truncate leading-tight font-medium mt-0.5" x-text="current.product"></p>
        </div>
        <button @click="visible = false" class="text-slate-500 hover:text-slate-300 p-0.5 rounded-full hover:bg-white/10 transition-colors shrink-0">
            <i data-lucide="x" class="w-3 h-3"></i>
        </button>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

        function globalApp() {
            return {
                lang: localStorage.getItem('nexus_lang') || 'en',
                cartCount: {{ array_sum(array_column(session('cart', []), 'quantity')) }},
                cartDrawerOpen: false,
                cartItems: @json(session('cart', [])),
                cartSubtotal: '{{ \App\Helpers\BanglaHelper::formatTaka(array_sum(array_column(session('cart', []), 'total'))) }}',
                rawSubtotal: {{ array_sum(array_column(session('cart', []), 'total')) }},
                spinModal: false,
                spinning: false,
                spinWon: false,
                spinMessage: '',
                spinCoupon: '',
                showScrollTop: false,

                initApp() {
                    this.refreshIcons();
                    window.addEventListener('scroll', () => {
                        this.showScrollTop = window.scrollY > 350;
                    });
                },

                toggleLang() {
                    this.lang = this.lang === 'en' ? 'bn' : 'en';
                    localStorage.setItem('nexus_lang', this.lang);
                },

                openCartDrawer() {
                    this.fetchDrawerData();
                    this.cartDrawerOpen = true;
                    this.refreshIcons();
                },

                fetchDrawerData() {
                    fetch('{{ route("cart.drawer_data") }}')
                        .then(res => res.json())
                        .then(data => {
                            this.cartItems = data.cart;
                            this.cartCount = data.cart_count;
                            this.cartSubtotal = data.subtotal;
                            this.rawSubtotal = data.raw_subtotal;
                            this.refreshIcons();
                        });
                },

                updateDrawerQty(key, qty) {
                    if (qty <= 0) {
                        this.removeFromDrawer(key);
                        return;
                    }

                    fetch('{{ route("cart.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ cart_key: key, quantity: qty })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.fetchDrawerData();
                    });
                },

                removeFromDrawer(key) {
                    fetch('{{ route("cart.remove") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ cart_key: key })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.fetchDrawerData();
                    });
                },

                triggerSpin() {
                    if (this.spinning) return;
                    this.spinning = true;
                    this.spinWon = false;

                    fetch('{{ route("lucky.spin") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        const wheel = document.getElementById('cyberWheel');
                        const deg = 1800 + (data.segment_index * 60) + 30;
                        wheel.style.transform = `rotate(${deg}deg)`;

                        setTimeout(() => {
                            this.spinning = false;
                            this.spinWon = true;
                            this.spinMessage = data.message;
                            this.spinCoupon = data.code;
                            
                            confetti({
                                particleCount: 120,
                                spread: 80,
                                origin: { y: 0.6 }
                            });
                        }, 4000);
                    });
                },

                copyCoupon(code) {
                    navigator.clipboard.writeText(code);
                    alert('Coupon code ' + code + ' copied to clipboard!');
                },

                refreshIcons() {
                    setTimeout(() => {
                        lucide.createIcons();
                    }, 50);
                }
            }
        }

        function liveSearch() {
            return {
                query: '',
                results: [],
                loading: false,
                open: false,

                search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }
                    this.loading = true;
                    fetch(`/api/search-live?query=${encodeURIComponent(this.query)}`)
                        .then(res => res.json())
                        .then(data => {
                            this.results = data;
                            this.loading = false;
                            this.open = true;
                        });
                }
            }
        }

        function aiAssistant() {
            return {
                chatOpen: false,
                botTyping: false,
                userInput: '',
                isHumanAssigned: false,
                messages: [],
                pollInterval: null,

                init() {
                    // Ready
                },

                toggleChat() {
                    this.chatOpen = !this.chatOpen;
                    if (this.chatOpen) {
                        this.initSession();
                        this.startPolling();
                    } else {
                        if (this.pollInterval) clearInterval(this.pollInterval);
                    }
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                },

                initSession() {
                    fetch('{{ route("live_chat.init") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            current_page: window.location.href,
                            cart_summary: Alpine.$data(document.querySelector('[x-data="globalApp()"]'))?.cartItems || []
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.messages = data.messages;
                            this.isHumanAssigned = data.is_assigned_to_human;
                            this.scrollBottom();
                            this.$nextTick(() => lucide.createIcons());
                        }
                    });
                },

                startPolling() {
                    if (this.pollInterval) clearInterval(this.pollInterval);
                    this.pollInterval = setInterval(() => {
                        if (!this.chatOpen) return;
                        const lastMsg = this.messages[this.messages.length - 1];
                        const lastId = lastMsg ? lastMsg.id : 0;

                        fetch(`{{ route("live_chat.poll") }}?last_id=${lastId}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.success && data.messages.length > 0) {
                                    this.messages.push(...data.messages);
                                    this.isHumanAssigned = data.is_assigned_to_human;
                                    this.scrollBottom();
                                    this.$nextTick(() => lucide.createIcons());
                                }
                            });
                    }, 3500);
                },

                askPreset(question) {
                    this.userInput = question;
                    this.sendMessage();
                },

                sendMessage() {
                    const text = this.userInput.trim();
                    if (!text) return;

                    this.messages.push({
                        id: Date.now(),
                        sender_type: 'customer',
                        sender_name: 'You',
                        message: text,
                        message_type: 'text'
                    });
                    this.userInput = '';
                    this.botTyping = true;
                    this.scrollBottom();

                    fetch('{{ route("live_chat.send_user") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ message: text })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.botTyping = false;
                        if (data.success) {
                            if (data.reply) {
                                this.messages.push(data.reply);
                            }
                            this.isHumanAssigned = data.is_assigned_to_human;
                            this.scrollBottom();
                            this.$nextTick(() => lucide.createIcons());
                        }
                    })
                    .catch(() => {
                        this.botTyping = false;
                    });
                },

                requestAgent() {
                    this.botTyping = true;
                    fetch('{{ route("live_chat.request_agent") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.botTyping = false;
                        if (data.success && data.reply) {
                            this.messages.push(data.reply);
                            this.isHumanAssigned = data.is_assigned_to_human;
                            this.scrollBottom();
                            this.$nextTick(() => lucide.createIcons());
                        }
                    });
                },

                formatMessage(text) {
                    if (!text) return '';
                    return text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
                },

                scrollBottom() {
                    this.$nextTick(() => {
                        const stream = document.getElementById('chatStream');
                        if (stream) stream.scrollTop = stream.scrollHeight;
                    });
                }
            }
        }

        function socialProof() {
            const isBn = '{{ \App\Helpers\LocalizationHelper::getLocale() }}' === 'bn';
            return {
                visible: false,
                items: isBn ? [
                    { name: 'তানভীর আহমেদ', location: 'গুলশান, ঢাকা', product: 'AuraBlade ANC Cyber Earbuds Pro', time: '২ মিনিট আগে', img: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=200&auto=format&fit=crop&q=80' },
                    { name: 'শাহরিয়ার কবির', location: 'আগ্রাবাদ, চট্টগ্রাম', product: 'Chronos-X AMOLED Smartwatch', time: '৫ মিনিট আগে', img: 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=200&auto=format&fit=crop&q=80' },
                    { name: 'নুসরাত জাহান', location: 'উত্তরা, ঢাকা', product: 'Vortex 75% Mechanical Keyboard', time: '৮ মিনিট আগে', img: 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=200&auto=format&fit=crop&q=80' },
                    { name: 'রফিকুল ইসলাম', location: 'সিলেট সদর', product: 'MechaCharge 130W Powerbank', time: '১২ মিনিট আগে', img: 'https://images.unsplash.com/photo-1609592426505-728b7e289bf5?w=200&auto=format&fit=crop&q=80' },
                ] : [
                    { name: 'Tanvir Ahmed', location: 'Gulshan, Dhaka', product: 'AuraBlade ANC Cyber Earbuds Pro', time: '2 mins ago', img: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=200&auto=format&fit=crop&q=80' },
                    { name: 'Shahriar Kabir', location: 'Agrabad, Chattogram', product: 'Chronos-X AMOLED Smartwatch', time: '5 mins ago', img: 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=200&auto=format&fit=crop&q=80' },
                    { name: 'Nusrat Jahan', location: 'Uttara, Dhaka', product: 'Vortex 75% Mechanical Keyboard', time: '8 mins ago', img: 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=200&auto=format&fit=crop&q=80' },
                    { name: 'Rafiqul Islam', location: 'Sylhet Sadar', product: 'MechaCharge 130W Powerbank', time: '12 mins ago', img: 'https://images.unsplash.com/photo-1609592426505-728b7e289bf5?w=200&auto=format&fit=crop&q=80' },
                ],
                current: {},

                init() {
                    setTimeout(() => {
                        this.triggerNext();
                    }, 2500);
                    setInterval(() => {
                        this.triggerNext();
                    }, 11000);
                },

                triggerNext() {
                    this.current = this.items[Math.floor(Math.random() * this.items.length)];
                    this.visible = true;
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                    setTimeout(() => {
                        this.visible = false;
                    }, 4000);
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
