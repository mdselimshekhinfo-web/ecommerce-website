<!DOCTYPE html>
<html lang="{{ \App\Helpers\LocalizationHelper::getLocale() }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', \App\Helpers\LocalizationHelper::get('admin_command_center') . ' // NEXUS DOKAN')</title>

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
                        bengali: ['"Hind Siliguri"', 'sans-serif'],
                    },
                    colors: {
                        admin: {
                            bg: '#06070d',
                            card: '#0d101b',
                            border: 'rgba(0, 242, 254, 0.2)',
                            cyan: '#00f2fe',
                            pink: '#ff007f',
                            purple: '#7928ca',
                            emerald: '#10b981',
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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #080c14;
            background-image: 
                radial-gradient(circle at 10% 10%, rgba(6, 182, 212, 0.02) 0%, transparent 40%),
                radial-gradient(circle at 90% 90%, rgba(99, 102, 241, 0.03) 0%, transparent 40%);
            color: #f1f5f9;
        }
        .admin-glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex text-slate-100 selection:bg-cyan-500 selection:text-black">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-slate-950/95 border-r border-slate-800/90 flex flex-col shrink-0 z-30 min-h-screen">
        
        <!-- Admin Brand -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800/80">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-400 via-indigo-500 to-purple-600 p-0.5 flex items-center justify-center shadow-lg group-hover:scale-105 transition-all">
                    <i data-lucide="shield-check" class="w-5 h-5 text-slate-950"></i>
                </div>
                <div>
                    <span class="font-cyber font-bold text-sm text-white tracking-wider block">NEXUS DOKAN</span>
                    <p class="text-[9px] font-mono text-cyan-400 uppercase tracking-widest">{{ \App\Helpers\LocalizationHelper::get('admin_brand_tagline') }}</p>
                </div>
            </a>
        </div>

        <!-- Navigation Links (Clean Accordion Menus) -->
        <nav class="flex-1 px-3 py-4 space-y-1.5 text-xs font-semibold overflow-y-auto"
             x-data="{ 
                 openMenu: '{{ request()->routeIs('admin.orders.*', 'admin.analytics.*', 'admin.live_chat.*', 'admin.ai_automation.*') ? 'sales' : (request()->routeIs('admin.products.*', 'admin.categories.*', 'admin.suppliers.*', 'admin.purchase_orders.*') ? 'inventory' : (request()->routeIs('admin.landing-pages.*', 'admin.abandoned_carts.*', 'admin.sms.*', 'admin.reviews.*', 'admin.coupons.*', 'admin.customers.*') ? 'marketing' : (request()->routeIs('admin.theme.*', 'admin.pages.*') ? 'appearance' : (request()->routeIs('admin.gateways.*', 'admin.marketing.pixels*', 'admin.settings.*', 'admin.staff.*', 'admin.fraud.*') ? 'settings' : '')))) }}',
                 toggle(menu) {
                     this.openMenu = (this.openMenu === menu) ? '' : menu;
                 }
             }">

            <!-- 1. Standalone Direct Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-500/15 text-cyan-400 border border-cyan-500/40 shadow-neon-cyan font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 text-cyan-400"></i>
                <span class="tracking-wide">{{ \App\Helpers\LocalizationHelper::get('admin_dashboard') }}</span>
            </a>

            <!-- 2. Accordion: Sales & Orders -->
            <div class="space-y-1">
                <button type="button" @click="toggle('sales')" 
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 transition-all font-mono text-[11px] uppercase tracking-wider">
                    <span class="flex items-center space-x-2.5">
                        <i data-lucide="shopping-cart" class="w-4 h-4 text-cyan-400"></i>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_sales_group') }}</span>
                    </span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="openMenu === 'sales' ? 'rotate-180 text-cyan-400' : 'text-slate-600'"></i>
                </button>

                <div x-show="openMenu === 'sales'" x-collapse class="pl-7 pr-2 py-1 space-y-1">
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_orders') }}</span>
                    </a>
                    <a href="{{ route('admin.live_chat.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.live_chat.*') ? 'bg-gradient-to-r from-cyan-500/20 to-purple-500/20 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_live_chat') }} 💬</span>
                    </a>
                    <a href="{{ route('admin.ai_automation.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.ai_automation.*') ? 'bg-gradient-to-r from-purple-500/20 to-pink-500/20 text-purple-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_ai_automation') }} ⚡</span>
                    </a>
                    <a href="{{ route('admin.analytics.pnl') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.analytics.*') ? 'bg-emerald-500/15 text-emerald-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_pnl') }}</span>
                    </a>
                </div>
            </div>

            <!-- 3. Accordion: Products & Inventory -->
            <div class="space-y-1">
                <button type="button" @click="toggle('inventory')" 
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 transition-all font-mono text-[11px] uppercase tracking-wider">
                    <span class="flex items-center space-x-2.5">
                        <i data-lucide="package" class="w-4 h-4 text-indigo-400"></i>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_inventory_group') }}</span>
                    </span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="openMenu === 'inventory' ? 'rotate-180 text-cyan-400' : 'text-slate-600'"></i>
                </button>

                <div x-show="openMenu === 'inventory'" x-collapse class="pl-7 pr-2 py-1 space-y-1">
                    <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.products.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_products') }}</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_categories') }}</span>
                    </a>
                    <a href="{{ route('admin.suppliers.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.suppliers.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_suppliers') }}</span>
                    </a>
                    <a href="{{ route('admin.purchase_orders.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.purchase_orders.*') ? 'bg-pink-500/15 text-pink-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_purchase_orders') }}</span>
                    </a>
                </div>
            </div>

            <!-- 4. Accordion: Marketing & Funnels -->
            <div class="space-y-1">
                <button type="button" @click="toggle('marketing')" 
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 transition-all font-mono text-[11px] uppercase tracking-wider">
                    <span class="flex items-center space-x-2.5">
                        <i data-lucide="zap" class="w-4 h-4 text-yellow-400"></i>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_marketing_group') }}</span>
                    </span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="openMenu === 'marketing' ? 'rotate-180 text-cyan-400' : 'text-slate-600'"></i>
                </button>

                <div x-show="openMenu === 'marketing'" x-collapse class="pl-7 pr-2 py-1 space-y-1">
                    <a href="{{ route('admin.landing-pages.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.landing-pages.*') ? 'bg-gradient-to-r from-pink-500/20 to-purple-500/20 text-pink-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_landing_pages') }}</span>
                    </a>
                    <a href="{{ route('admin.abandoned_carts.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.abandoned_carts.*') ? 'bg-emerald-500/15 text-emerald-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_cart_recovery') }}</span>
                    </a>
                    <a href="{{ route('admin.sms.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.sms.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_sms_hub') }}</span>
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-amber-500/15 text-amber-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_reviews') }}</span>
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.coupons.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_coupons') }}</span>
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_vip_crm') }}</span>
                    </a>
                </div>
            </div>

            <!-- 5. Accordion: Appearance & Pages -->
            <div class="space-y-1">
                <button type="button" @click="toggle('appearance')" 
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 transition-all font-mono text-[11px] uppercase tracking-wider">
                    <span class="flex items-center space-x-2.5">
                        <i data-lucide="palette" class="w-4 h-4 text-pink-400"></i>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_appearance_group') }}</span>
                    </span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="openMenu === 'appearance' ? 'rotate-180 text-cyan-400' : 'text-slate-600'"></i>
                </button>

                <div x-show="openMenu === 'appearance'" x-collapse class="pl-7 pr-2 py-1 space-y-1">
                    <a href="{{ route('admin.theme.studio') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.theme.*') ? 'bg-pink-500/20 text-pink-300 font-bold shadow-neon-pink' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_theme_studio') }}</span>
                    </a>
                    <a href="{{ route('admin.pages.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.pages.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_policy_pages') }}</span>
                    </a>
                </div>
            </div>

            <!-- 6. Accordion: Settings & Integrations -->
            <div class="space-y-1">
                <button type="button" @click="toggle('settings')" 
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 transition-all font-mono text-[11px] uppercase tracking-wider">
                    <span class="flex items-center space-x-2.5">
                        <i data-lucide="settings" class="w-4 h-4 text-emerald-400"></i>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_settings_group') }}</span>
                    </span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="openMenu === 'settings' ? 'rotate-180 text-cyan-400' : 'text-slate-600'"></i>
                </button>

                <div x-show="openMenu === 'settings'" x-collapse class="pl-7 pr-2 py-1 space-y-1">
                    <a href="{{ route('admin.gateways.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.gateways.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_gateway_hub') }}</span>
                    </a>
                    <a href="{{ route('admin.marketing.pixels') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.marketing.pixels*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_pixel_hub') }}</span>
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.staff.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_staff') }}</span>
                    </a>
                    <a href="{{ route('admin.fraud.blacklist') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.fraud.*') ? 'bg-pink-500/15 text-pink-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_blacklist') }}</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-cyan-500/15 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <span>•</span>
                        <span>{{ \App\Helpers\LocalizationHelper::get('admin_store_settings') }}</span>
                    </a>
                </div>
            </div>

            <!-- Direct Link: Live Store -->
            <div class="pt-3 border-t border-slate-800/80">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center space-x-2.5 px-3.5 py-2 rounded-xl text-emerald-400 hover:bg-emerald-500/10 transition-all font-mono text-xs">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    <span>{{ \App\Helpers\LocalizationHelper::get('admin_live_store') }} ↗</span>
                </a>
            </div>

        </nav>

        <!-- User / Admin Profile Card -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <img src="{{ Auth::user()->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-8 h-8 rounded-lg object-cover border border-cyan-400/40">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-cyan-400 font-mono uppercase">{{ \App\Helpers\LocalizationHelper::get('admin_super_admin') }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-500 hover:text-red-400 rounded-lg hover:bg-red-500/10" title="{{ \App\Helpers\LocalizationHelper::get('admin_logout') }}">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Bar with Compact Language Toggle -->
        <header class="h-20 bg-slate-950/70 border-b border-slate-800 flex items-center justify-between px-6 sm:px-8 backdrop-blur-md sticky top-0 z-20">
            <div>
                <h2 class="font-cyber font-bold text-base sm:text-lg text-white">@yield('page-title', \App\Helpers\LocalizationHelper::get('admin_dashboard'))</h2>
                <p class="text-[11px] text-slate-400 font-mono">{{ \App\Helpers\LocalizationHelper::get('admin_dhaka_time') }}: {{ now()->timezone('Asia/Dhaka')->format('d M Y, h:i A') }}</p>
            </div>

            <div class="flex items-center space-x-3">
                
                <!-- Sleek Minimalist 1-Click Language Switcher (Compact Pill) -->
                @php
                    $isBn = \App\Helpers\LocalizationHelper::getLocale() === 'bn';
                @endphp
                <a href="{{ route('language.toggle') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900 border border-slate-700/80 hover:border-cyan-400/60 text-xs font-mono font-bold transition-all shadow-sm group select-none cursor-pointer"
                   title="{{ $isBn ? 'Switch to English' : 'বাংলায় পরিবর্তন করুন' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isBn ? 'bg-emerald-400 animate-pulse' : 'bg-cyan-400' }}"></span>
                    <span class="text-slate-300 group-hover:text-white">{{ $isBn ? '🇧🇩 বাংলা' : '🇬🇧 English' }}</span>
                    <i data-lucide="arrow-left-right" class="w-3.5 h-3.5 text-slate-500 group-hover:text-cyan-400 transition-colors"></i>
                </a>

                <!-- Live Storefront Badge -->
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:flex px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-mono font-bold items-center space-x-1.5 hover:bg-emerald-500/20 transition-all">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>{{ \App\Helpers\LocalizationHelper::get('admin_live_store') }} ↗</span>
                </a>

            </div>
        </header>

        <!-- Dynamic Body Content -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8">
            @if(session('success'))
                <div class="p-4 mb-6 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 text-xs flex items-center justify-between shadow-lg">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="$el.parentElement.remove()" class="text-emerald-400 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-6 rounded-xl bg-red-950/80 border border-red-500/40 text-red-300 text-xs shadow-lg">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
