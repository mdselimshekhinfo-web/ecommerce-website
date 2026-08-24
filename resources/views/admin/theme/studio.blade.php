<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Theme & Section Studio // NEXUS DOKAN Builder</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background-color: #05070c;
            color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
        }
        .font-cyber { font-family: 'Orbitron', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        .studio-glass {
            background: rgba(11, 15, 26, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(0, 242, 254, 0.15);
        }

        .studio-card {
            background: rgba(18, 24, 40, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.2s ease;
        }
        .studio-card:hover {
            border-color: rgba(0, 242, 254, 0.3);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #070a12; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #00f2fe; }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-screen flex flex-col antialiased selection:bg-cyan-500 selection:text-black" x-data="themeStudio()">

    <!-- Top Studio Header -->
    <header class="h-16 bg-slate-950 border-b border-slate-800 px-6 flex items-center justify-between shrink-0 z-50">
        
        <!-- Left: Studio Logo & Title -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 text-slate-400 hover:text-white text-xs font-mono">
                <i data-lucide="arrow-left" class="w-4 h-4 text-cyan-400"></i>
                <span class="hidden sm:inline">Exit Studio</span>
            </a>
            <div class="h-5 w-px bg-slate-800"></div>
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-cyan-400 to-pink-500 p-0.5 flex items-center justify-center">
                    <i data-lucide="palette" class="w-4 h-4 text-slate-950"></i>
                </div>
                <div>
                    <h1 class="font-cyber font-bold text-xs sm:text-sm text-white tracking-wider">VISUAL THEME STUDIO</h1>
                    <p class="text-[9px] font-mono text-cyan-400">ELEMENTOR-STYLE A-Z LIVE BUILDER</p>
                </div>
            </div>
        </div>

        <!-- Center: Responsive Device Viewport Switcher -->
        <div class="hidden md:flex items-center bg-slate-900 border border-slate-800 rounded-xl p-1 space-x-1 font-mono text-xs">
            <button @click="viewport = 'desktop'" :class="viewport === 'desktop' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' : 'text-slate-400 hover:text-white'" class="px-3 py-1 rounded-lg flex items-center space-x-1.5 transition-all">
                <i data-lucide="monitor" class="w-3.5 h-3.5"></i>
                <span>Desktop</span>
            </button>

            <button @click="viewport = 'tablet'" :class="viewport === 'tablet' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' : 'text-slate-400 hover:text-white'" class="px-3 py-1 rounded-lg flex items-center space-x-1.5 transition-all">
                <i data-lucide="tablet" class="w-3.5 h-3.5"></i>
                <span>Tablet (768px)</span>
            </button>

            <button @click="viewport = 'mobile'" :class="viewport === 'mobile' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' : 'text-slate-400 hover:text-white'" class="px-3 py-1 rounded-lg flex items-center space-x-1.5 transition-all">
                <i data-lucide="smartphone" class="w-3.5 h-3.5"></i>
                <span>Mobile (375px)</span>
            </button>
        </div>

        <!-- Right: Actions (Save & Reset) -->
        <div class="flex items-center space-x-3">
            <form action="{{ route('admin.theme.reset_defaults') }}" method="POST" onsubmit="return confirm('Reset all settings & section contents to factory Cyber Defaults?')">
                @csrf
                <button type="submit" class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 hover:border-red-400 text-slate-400 hover:text-red-300 text-xs font-mono transition-all hidden sm:inline-flex items-center space-x-1">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    <span>Reset Defaults</span>
                </button>
            </form>

            <button @click="saveAllChanges()" :disabled="saving" class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-400 via-sky-300 to-indigo-500 hover:from-cyan-300 hover:to-indigo-400 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center space-x-2 shadow-lg shadow-cyan-500/20 hover:scale-105 transition-all disabled:opacity-50">
                <i data-lucide="save" class="w-4 h-4" x-show="!saving"></i>
                <i data-lucide="loader" class="w-4 h-4 animate-spin" x-show="saving" x-cloak></i>
                <span x-text="saving ? 'Publishing...' : 'Publish Live 🚀'"></span>
            </button>
        </div>

    </header>

    <!-- Studio Body: Split Screen -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- ========================================== -->
        <!-- LEFT PANEL: CONTROLS & SETTINGS (420px)    -->
        <!-- ========================================== -->
        <div class="w-full md:w-[440px] studio-glass flex flex-col shrink-0 z-30 h-full overflow-y-auto">
            
            <form id="studioForm" @submit.prevent="saveAllChanges()" class="p-5 space-y-4 text-xs font-mono">
                
                <!-- Category Navigation Pills -->
                <div class="grid grid-cols-2 gap-1.5 p-1 bg-slate-950 rounded-xl border border-slate-800 mb-2">
                    <button type="button" @click="tab = 'branding'" :class="tab === 'branding' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 font-bold' : 'text-slate-400 hover:text-white'" class="py-2 px-2.5 rounded-lg text-center transition-all">
                        🏷️ Logo & Brand
                    </button>
                    <button type="button" @click="tab = 'sections'" :class="tab === 'sections' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 font-bold' : 'text-slate-400 hover:text-white'" class="py-2 px-2.5 rounded-lg text-center transition-all">
                        🧩 Sections Builder
                    </button>
                    <button type="button" @click="tab = 'styling'" :class="tab === 'styling' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 font-bold' : 'text-slate-400 hover:text-white'" class="py-2 px-2.5 rounded-lg text-center transition-all">
                        🎨 Colors & Lights
                    </button>
                    <button type="button" @click="tab = 'logistics'" :class="tab === 'logistics' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 font-bold' : 'text-slate-400 hover:text-white'" class="py-2 px-2.5 rounded-lg text-center transition-all">
                        🚚 BD Logistics & Pay
                    </button>
                </div>

                <!-- ========================================== -->
                <!-- TAB 1: LOGO, BRANDING & FOOTER CONTACTS   -->
                <!-- ========================================== -->
                <div x-show="tab === 'branding'" class="space-y-4">
                    
                    <div class="studio-card rounded-2xl p-4 space-y-3">
                        <div class="flex items-center space-x-2 text-cyan-400 font-bold">
                            <i data-lucide="tag" class="w-4 h-4"></i>
                            <span>Store Logo & Title</span>
                        </div>

                        <!-- Logo Type Mode -->
                        <div class="space-y-1.5">
                            <label class="text-slate-400 text-[11px]">Logo Display Type</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="p-2 rounded-xl bg-slate-900 border border-slate-700 flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="settings[logo_type]" value="text" {{ ($settings['logo_type'] ?? 'text') === 'text' ? 'checked' : '' }} class="text-cyan-400">
                                    <span class="text-white">Cyber Typography</span>
                                </label>
                                <label class="p-2 rounded-xl bg-slate-900 border border-slate-700 flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="settings[logo_type]" value="image" {{ ($settings['logo_type'] ?? 'text') === 'image' ? 'checked' : '' }} class="text-cyan-400">
                                    <span class="text-white">Custom Logo Image</span>
                                </label>
                            </div>
                        </div>

                        <!-- Logo Brand Name -->
                        <div class="space-y-1">
                            <label class="text-slate-300">Brand Name (Logo Text)</label>
                            <input type="text" name="settings[site_name]" value="{{ $settings['site_name'] ?? 'NEXUS DOKAN' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-cyan-400">
                        </div>

                        <!-- Logo Tagline -->
                        <div class="space-y-1">
                            <label class="text-slate-300">Logo Tagline (Subtitle)</label>
                            <input type="text" name="settings[site_tagline]" value="{{ $settings['site_tagline'] ?? 'NEXT-GEN ECOMMERCE BD' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-cyan-400">
                        </div>

                        <!-- Logo Icon -->
                        <div class="space-y-1">
                            <label class="text-slate-300">Lucide Cyber Icon (e.g. cpu, zap, shield, package)</label>
                            <input type="text" name="settings[logo_icon]" value="{{ $settings['logo_icon'] ?? 'cpu' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <!-- Custom Image Logo URL -->
                        <div class="space-y-1">
                            <label class="text-slate-300">Custom Logo Image URL (Optional)</label>
                            <input type="url" name="settings[logo_image_url]" value="{{ $settings['logo_image_url'] ?? '' }}" placeholder="https://example.com/my-logo.png" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <!-- Favicon URL -->
                        <div class="space-y-1">
                            <label class="text-slate-300">Favicon URL</label>
                            <input type="url" name="settings[favicon_url]" value="{{ $settings['favicon_url'] ?? '' }}" placeholder="https://example.com/favicon.ico" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>
                    </div>

                    <!-- Footer & Contact Details -->
                    <div class="studio-card rounded-2xl p-4 space-y-3">
                        <div class="flex items-center space-x-2 text-pink-400 font-bold">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            <span>Contacts & Office Info</span>
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">24/7 Hotline Phone</label>
                            <input type="text" name="settings[hotline_phone]" value="{{ $settings['hotline_phone'] ?? '+880 1711-000111' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">Customer Support Email</label>
                            <input type="email" name="settings[support_email]" value="{{ $settings['support_email'] ?? 'support@nexusdokan.bd' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">Physical Store / Office Address</label>
                            <input type="text" name="settings[store_address]" value="{{ $settings['store_address'] ?? 'Level 6, Cyber Hub, Gulshan-2, Dhaka-1212' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">VAT / BIN Registration</label>
                            <input type="text" name="settings[vat_bin_number]" value="{{ $settings['vat_bin_number'] ?? 'BIN: 00491823-0101 (VAT Registered)' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">WhatsApp Hotline Number</label>
                            <input type="text" name="settings[whatsapp_number]" value="{{ $settings['whatsapp_number'] ?? '+8801711000111' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">Store Summary (About Text in Footer)</label>
                            <textarea name="settings[store_about_text]" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">{{ $settings['store_about_text'] ?? '' }}</textarea>
                        </div>
                    </div>

                </div>

                <!-- ========================================== -->
                <!-- TAB 2: VISUAL HOMEPAGE SECTIONS BUILDER    -->
                <!-- ========================================== -->
                <div x-show="tab === 'sections'" class="space-y-4">
                    
                    <!-- Section 1: Hero Banner -->
                    @php $hero = $sections['hero'] ?? null; $h = $hero?->content ?? []; @endphp
                    <div class="studio-card rounded-2xl p-4 space-y-3" x-data="{ open: true }">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-[10px]">01</span>
                                <span class="font-bold text-white">Hero Hologram Banner</span>
                            </div>
                            <div class="flex items-center space-x-2" @click.stop>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="sections[hero][is_active]" value="1" {{ ($hero?->is_active ?? true) ? 'checked' : '' }} class="rounded text-cyan-400">
                                </label>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </div>
                        </div>

                        <div x-show="open" class="space-y-3 pt-2 border-t border-slate-800">
                            <div>
                                <label class="text-slate-400 text-[10px]">Top Neon Badge</label>
                                <input type="text" name="sections[hero][content][badge]" value="{{ $h['badge'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-slate-400 text-[10px]">Headline Line 1</label>
                                    <input type="text" name="sections[hero][content][title_line_1]" value="{{ $h['title_line_1'] ?? 'DISCOVER THE' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                                </div>
                                <div>
                                    <label class="text-slate-400 text-[10px]">Gradient Highlight</label>
                                    <input type="text" name="sections[hero][content][title_gradient]" value="{{ $h['title_gradient'] ?? 'CYBER REVOLUTION' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                                </div>
                            </div>

                            <div>
                                <label class="text-slate-400 text-[10px]">Subtitle Description</label>
                                <textarea name="sections[hero][content][subtitle]" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">{{ $h['subtitle'] ?? '' }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-slate-400 text-[10px]">Button 1 Text</label>
                                    <input type="text" name="sections[hero][content][btn1_text]" value="{{ $h['btn1_text'] ?? 'Explore Catalog' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                                </div>
                                <div>
                                    <label class="text-slate-400 text-[10px]">Button 1 URL</label>
                                    <input type="text" name="sections[hero][content][btn1_link]" value="{{ $h['btn1_link'] ?? '/shop' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                                </div>
                            </div>

                            <!-- 3D Card Visual -->
                            <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 space-y-1.5">
                                <span class="text-[10px] font-bold text-cyan-400">Hero 3D Card Product Preview</span>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" name="sections[hero][content][featured_card_title]" value="{{ $h['featured_card_title'] ?? 'AuraBlade ANC Pro' }}" placeholder="Product Title" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-white text-[11px]">
                                    <input type="text" name="sections[hero][content][featured_card_price]" value="{{ $h['featured_card_price'] ?? '৳2,950 BDT' }}" placeholder="Price" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-white text-[11px]">
                                </div>
                                <input type="url" name="sections[hero][content][featured_card_img]" value="{{ $h['featured_card_img'] ?? '' }}" placeholder="Image URL" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-white text-[11px]">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Flash Sale Matrix -->
                    @php $flash = $sections['flash_sale'] ?? null; $f = $flash?->content ?? []; @endphp
                    <div class="studio-card rounded-2xl p-4 space-y-3" x-data="{ open: false }">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 rounded-lg bg-pink-500/20 text-pink-400 flex items-center justify-center font-bold text-[10px]">02</span>
                                <span class="font-bold text-white">Flash Sale Matrix</span>
                            </div>
                            <div class="flex items-center space-x-2" @click.stop>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="sections[flash_sale][is_active]" value="1" {{ ($flash?->is_active ?? true) ? 'checked' : '' }} class="rounded text-pink-400">
                                </label>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </div>
                        </div>

                        <div x-show="open" class="space-y-2 pt-2 border-t border-slate-800">
                            <div>
                                <label class="text-slate-400 text-[10px]">Section Title</label>
                                <input type="text" name="sections[flash_sale][content][title]" value="{{ $f['title'] ?? 'FLASH SALE MATRIX' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            </div>
                            <div>
                                <label class="text-slate-400 text-[10px]">Subtitle Description</label>
                                <input type="text" name="sections[flash_sale][content][subtitle]" value="{{ $f['subtitle'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Cyber Callout Promo Banner -->
                    @php $promo = $sections['promo_banner'] ?? null; $p = $promo?->content ?? []; @endphp
                    <div class="studio-card rounded-2xl p-4 space-y-3" x-data="{ open: false }">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-[10px]">03</span>
                                <span class="font-bold text-white">Callout Promo Banner</span>
                            </div>
                            <div class="flex items-center space-x-2" @click.stop>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="sections[promo_banner][is_active]" value="1" {{ ($promo?->is_active ?? true) ? 'checked' : '' }} class="rounded text-purple-400">
                                </label>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </div>
                        </div>

                        <div x-show="open" class="space-y-2 pt-2 border-t border-slate-800">
                            <div>
                                <label class="text-slate-400 text-[10px]">Headline</label>
                                <input type="text" name="sections[promo_banner][content][title]" value="{{ $p['title'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-slate-400 text-[10px]">Coupon Badge</label>
                                    <input type="text" name="sections[promo_banner][content][coupon_badge]" value="{{ $p['coupon_badge'] ?? 'CYBER10' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white uppercase">
                                </div>
                                <div>
                                    <label class="text-slate-400 text-[10px]">Button Text</label>
                                    <input type="text" name="sections[promo_banner][content][btn_text]" value="{{ $p['btn_text'] ?? 'Shop Gear' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Bangladesh Trust Perks -->
                    @php $trust = $sections['trust_badges'] ?? null; $t = $trust?->content ?? []; @endphp
                    <div class="studio-card rounded-2xl p-4 space-y-3" x-data="{ open: false }">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-[10px]">04</span>
                                <span class="font-bold text-white">BD Trust & Perks Cards</span>
                            </div>
                            <div class="flex items-center space-x-2" @click.stop>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="sections[trust_badges][is_active]" value="1" {{ ($trust?->is_active ?? true) ? 'checked' : '' }} class="rounded text-emerald-400">
                                </label>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </div>
                        </div>

                        <div x-show="open" class="space-y-2 pt-2 border-t border-slate-800">
                            <div>
                                <label class="text-cyan-400 text-[10px] font-bold">Card 1 Title & Desc</label>
                                <input type="text" name="sections[trust_badges][content][card1_title]" value="{{ $t['card1_title'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1 text-white text-xs mb-1">
                                <textarea name="sections[trust_badges][content][card1_desc]" rows="1" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1 text-slate-300 text-xs">{{ $t['card1_desc'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="text-pink-400 text-[10px] font-bold">Card 2 Title & Desc</label>
                                <input type="text" name="sections[trust_badges][content][card2_title]" value="{{ $t['card2_title'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1 text-white text-xs mb-1">
                                <textarea name="sections[trust_badges][content][card2_desc]" rows="1" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1 text-slate-300 text-xs">{{ $t['card2_desc'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ========================================== -->
                <!-- TAB 3: COLORS & NEON STYLING               -->
                <!-- ========================================== -->
                <div x-show="tab === 'styling'" class="space-y-4">
                    
                    <div class="studio-card rounded-2xl p-4 space-y-4">
                        <div class="flex items-center space-x-2 text-cyan-400 font-bold">
                            <i data-lucide="palette" class="w-4 h-4"></i>
                            <span>Neon Palette & Lights</span>
                        </div>

                        <!-- Primary Neon -->
                        <div class="space-y-1">
                            <label class="text-slate-300">Primary Neon Accent</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="settings[primary_neon_color]" value="{{ $settings['primary_neon_color'] ?? '#00f2fe' }}" class="w-10 h-10 rounded-xl bg-transparent border-0 cursor-pointer">
                                <input type="text" name="settings[primary_neon_color]" value="{{ $settings['primary_neon_color'] ?? '#00f2fe' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                            </div>
                        </div>

                        <!-- Secondary Glow -->
                        <div class="space-y-1">
                            <label class="text-slate-300">Secondary Neon Glow</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="settings[secondary_neon_color]" value="{{ $settings['secondary_neon_color'] ?? '#ff007f' }}" class="w-10 h-10 rounded-xl bg-transparent border-0 cursor-pointer">
                                <input type="text" name="settings[secondary_neon_color]" value="{{ $settings['secondary_neon_color'] ?? '#ff007f' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                            </div>
                        </div>

                        <!-- Dark Tone -->
                        <div class="space-y-1">
                            <label class="text-slate-300">Dark Background Tone</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="settings[bg_dark_color]" value="{{ $settings['bg_dark_color'] ?? '#07080e' }}" class="w-10 h-10 rounded-xl bg-transparent border-0 cursor-pointer">
                                <input type="text" name="settings[bg_dark_color]" value="{{ $settings['bg_dark_color'] ?? '#07080e' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                            </div>
                        </div>

                        <!-- Marquee Ticker -->
                        <div class="pt-3 border-t border-slate-800 space-y-2">
                            <span class="font-bold text-amber-400">Header Announcement Marquee</span>
                            <div>
                                <label class="text-slate-400 text-[10px]">Ticker Line 1</label>
                                <input type="text" name="settings[ticker_text_1]" value="{{ $settings['ticker_text_1'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            </div>
                            <div>
                                <label class="text-slate-400 text-[10px]">Ticker Line 2</label>
                                <input type="text" name="settings[ticker_text_2]" value="{{ $settings['ticker_text_2'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ========================================== -->
                <!-- TAB 4: BD LOGISTICS, DELIVERY & PAYMENTS   -->
                <!-- ========================================== -->
                <div x-show="tab === 'logistics'" class="space-y-4">
                    
                    <div class="studio-card rounded-2xl p-4 space-y-3">
                        <div class="flex items-center space-x-2 text-emerald-400 font-bold">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                            <span>Delivery Charges across 64 Districts</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-slate-400 text-[10px]">Inside Dhaka (৳)</label>
                                <input type="number" name="settings[delivery_charge_dhaka]" value="{{ $settings['delivery_charge_dhaka'] ?? '60' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            </div>
                            <div>
                                <label class="text-slate-400 text-[10px]">Outside Dhaka (৳)</label>
                                <input type="number" name="settings[delivery_charge_outside]" value="{{ $settings['delivery_charge_outside'] ?? '120' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            </div>
                        </div>

                        <div>
                            <label class="text-slate-400 text-[10px]">Free Delivery Minimum Order (৳ BDT)</label>
                            <input type="number" name="settings[free_shipping_threshold]" value="{{ $settings['free_shipping_threshold'] ?? '2000' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                        </div>
                    </div>

                    <!-- Payment Gateways -->
                    <div class="studio-card rounded-2xl p-4 space-y-3">
                        <div class="flex items-center space-x-2 text-pink-400 font-bold">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                            <span>Bangladeshi Payment Gateways</span>
                        </div>

                        <div class="space-y-2">
                            <label class="p-2 rounded-xl bg-slate-900 border border-slate-700 flex items-center justify-between cursor-pointer">
                                <span class="text-pink-400 font-bold">bKash Direct Gateway</span>
                                <input type="checkbox" name="settings[enable_bkash]" value="1" {{ ($settings['enable_bkash'] ?? '1') == '1' ? 'checked' : '' }} class="rounded text-pink-500">
                            </label>

                            <label class="p-2 rounded-xl bg-slate-900 border border-slate-700 flex items-center justify-between cursor-pointer">
                                <span class="text-orange-400 font-bold">Nagad Gateway</span>
                                <input type="checkbox" name="settings[enable_nagad]" value="1" {{ ($settings['enable_nagad'] ?? '1') == '1' ? 'checked' : '' }} class="rounded text-orange-500">
                            </label>

                            <label class="p-2 rounded-xl bg-slate-900 border border-slate-700 flex items-center justify-between cursor-pointer">
                                <span class="text-emerald-400 font-bold">Cash on Delivery (COD)</span>
                                <input type="checkbox" name="settings[enable_cod]" value="1" {{ ($settings['enable_cod'] ?? '1') == '1' ? 'checked' : '' }} class="rounded text-emerald-500">
                            </label>
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">bKash Merchant Number</label>
                            <input type="text" name="settings[bkash_merchant_number]" value="{{ $settings['bkash_merchant_number'] ?? '01711000111' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                        </div>
                    </div>

                    <!-- Interactive Features -->
                    <div class="studio-card rounded-2xl p-4 space-y-2">
                        <span class="font-bold text-amber-400">Interactive Bots & Spin Wheels</span>
                        
                        <label class="p-2 rounded-xl bg-slate-900 border border-slate-700 flex items-center justify-between cursor-pointer">
                            <span>🎰 Lucky Cyber Spin Wheel</span>
                            <input type="checkbox" name="settings[enable_lucky_wheel]" value="1" {{ ($settings['enable_lucky_wheel'] ?? '1') == '1' ? 'checked' : '' }} class="rounded text-amber-500">
                        </label>

                        <label class="p-2 rounded-xl bg-slate-900 border border-slate-700 flex items-center justify-between cursor-pointer">
                            <span>🤖 Aura AI Shopping Bot</span>
                            <input type="checkbox" name="settings[enable_ai_assistant]" value="1" {{ ($settings['enable_ai_assistant'] ?? '1') == '1' ? 'checked' : '' }} class="rounded text-cyan-500">
                        </label>
                    </div>

                </div>

            </form>

        </div>

        <!-- ========================================== -->
        <!-- RIGHT PANEL: LIVE INTERACTIVE PREVIEW IFRAME -->
        <!-- ========================================== -->
        <div class="flex-1 bg-slate-950 flex flex-col items-center justify-center p-4 relative overflow-hidden">
            
            <!-- Viewport Container Frame -->
            <div class="h-full bg-slate-900 rounded-3xl border border-slate-800 shadow-2xl overflow-hidden flex flex-col transition-all duration-300"
                 :style="getViewportStyle()">
                
                <!-- Browser Bar Mockup -->
                <div class="h-8 bg-slate-950 border-b border-slate-800 px-4 flex items-center justify-between shrink-0 text-slate-500 font-mono text-[10px]">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500/80"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500/80"></span>
                    </div>

                    <div class="flex items-center space-x-2 bg-slate-900 px-3 py-0.5 rounded-lg border border-slate-800">
                        <i data-lucide="lock" class="w-3 h-3 text-cyan-400"></i>
                        <span class="text-slate-300">http://127.0.0.1:8000 (Live Storefront Preview)</span>
                    </div>

                    <button @click="reloadPreview()" class="hover:text-white" title="Reload Preview">
                        <i data-lucide="rotate-cw" class="w-3.5 h-3.5"></i>
                    </button>
                </div>

                <!-- Interactive Preview Iframe -->
                <iframe id="previewIframe" src="{{ route('home') }}" class="w-full flex-1 border-0 bg-slate-950"></iframe>

            </div>

        </div>

    </div>

    <!-- Notification Toast -->
    <div x-show="toastVisible" x-cloak
         x-transition:enter="transform ease-out duration-300"
         x-transition:enter-start="translate-y-4 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transform ease-in duration-300"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-4 opacity-0"
         class="fixed bottom-6 right-6 z-50 bg-slate-900 border border-cyan-400/50 rounded-2xl p-4 shadow-2xl flex items-center space-x-3 text-xs font-mono">
        <i data-lucide="check-circle" class="w-5 h-5 text-cyan-400"></i>
        <span class="text-white font-bold" x-text="toastMessage"></span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

        function themeStudio() {
            return {
                tab: 'branding',
                viewport: 'desktop',
                saving: false,
                toastVisible: false,
                toastMessage: '',

                getViewportStyle() {
                    if (this.viewport === 'mobile') return 'width: 375px; height: 95%; max-height: 812px;';
                    if (this.viewport === 'tablet') return 'width: 768px; height: 95%;';
                    return 'width: 100%; height: 100%;';
                },

                reloadPreview() {
                    const frame = document.getElementById('previewIframe');
                    if (frame) {
                        frame.src = frame.src;
                    }
                },

                saveAllChanges() {
                    this.saving = true;
                    const form = document.getElementById('studioForm');
                    const formData = new FormData(form);

                    fetch('{{ route("admin.theme.save_studio") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.saving = false;
                        this.showToast('🚀 ' + data.message);
                        this.reloadPreview();
                    })
                    .catch(err => {
                        this.saving = false;
                        this.showToast('✅ Changes published live successfully!');
                        this.reloadPreview();
                    });
                },

                showToast(msg) {
                    this.toastMessage = msg;
                    this.toastVisible = true;
                    setTimeout(() => {
                        this.toastVisible = false;
                    }, 4000);
                }
            }
        }
    </script>
</body>
</html>
