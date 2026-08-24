@extends('layouts.admin')

@section('page-title', \App\Helpers\LocalizationHelper::get('admin_pixel_hub'))

@section('content')
<div class="space-y-8" x-data="{ showAddModal: false }">

    <!-- Top Action Banner -->
    <div class="admin-glass rounded-3xl p-6 border border-cyan-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center text-cyan-400">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="font-cyber font-bold text-lg text-white">{{ \App\Helpers\LocalizationHelper::get('pixel_hub_title') }}</h2>
                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ \App\Helpers\LocalizationHelper::get('pixel_hub_subtitle') }}</p>
            </div>
        </div>

        <button @click="showAddModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>{{ \App\Helpers\LocalizationHelper::get('add_custom_pixel_btn') }}</span>
        </button>
    </div>

    <!-- Grid of Modular Cards (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- 1. Meta (Facebook) Pixel & CAPI Card -->
        @php
            $fbActive = !isset($settings['fb_pixel_active']) || $settings['fb_pixel_active'] === '1';
        @endphp
        <div class="admin-glass rounded-3xl p-6 border {{ $fbActive ? 'border-blue-500/40 bg-slate-900/60' : 'border-slate-800 bg-slate-950/60 opacity-80' }} space-y-5 transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 border border-blue-500/30 flex items-center justify-center">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('meta_pixel_title') }}</h3>
                        <span class="text-[10px] font-mono text-slate-400">{{ \App\Helpers\LocalizationHelper::get('meta_pixel_sub') }}</span>
                    </div>
                </div>

                <!-- Instant Toggle Switch -->
                <form action="{{ route('admin.marketing.pixels.toggle', 'fb_pixel_active') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-mono font-bold transition-all {{ $fbActive ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                        {{ $fbActive ? \App\Helpers\LocalizationHelper::get('live_active') : \App\Helpers\LocalizationHelper::get('disabled') }}
                    </button>
                </form>
            </div>

            <!-- Single Save Form -->
            <form action="{{ route('admin.marketing.pixels.update_single') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                <input type="hidden" name="tracker_name" value="{{ \App\Helpers\LocalizationHelper::get('meta_pixel_title') }}">

                <div class="space-y-1">
                    <label class="text-slate-300">{{ \App\Helpers\LocalizationHelper::get('fb_pixel_id_label') }}</label>
                    <input type="text" name="fb_pixel_id" value="{{ $settings['fb_pixel_id'] ?? '' }}" placeholder="e.g. 102938475619283" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-bold focus:border-cyan-400">
                    <p class="text-[10px] text-slate-500">{{ \App\Helpers\LocalizationHelper::get('fb_pixel_id_help') }}</p>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">{{ \App\Helpers\LocalizationHelper::get('fb_capi_token_label') }}</label>
                    <textarea name="fb_capi_token" rows="2" placeholder="EAAB..." 
                              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white text-[11px]">{{ $settings['fb_capi_token'] ?? '' }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 border border-blue-500/40 font-cyber font-bold text-xs uppercase tracking-wider transition-all">
                        {{ \App\Helpers\LocalizationHelper::get('save_meta_pixel_btn') }}
                    </button>

                    <a href="{{ route('admin.marketing.pixels.test', 'Meta Pixel') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all">
                        {{ \App\Helpers\LocalizationHelper::get('test_btn') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- 2. Google Tag Manager (GTM) Card -->
        @php
            $gtmActive = !isset($settings['gtm_active']) || $settings['gtm_active'] === '1';
        @endphp
        <div class="admin-glass rounded-3xl p-6 border {{ $gtmActive ? 'border-amber-500/40 bg-slate-900/60' : 'border-slate-800 bg-slate-950/60 opacity-80' }} space-y-5 transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center">
                        <i data-lucide="tag" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('gtm_title') }}</h3>
                        <span class="text-[10px] font-mono text-slate-400">{{ \App\Helpers\LocalizationHelper::get('gtm_sub') }}</span>
                    </div>
                </div>

                <form action="{{ route('admin.marketing.pixels.toggle', 'gtm_active') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-mono font-bold transition-all {{ $gtmActive ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                        {{ $gtmActive ? \App\Helpers\LocalizationHelper::get('live_active') : \App\Helpers\LocalizationHelper::get('disabled') }}
                    </button>
                </form>
            </div>

            <form action="{{ route('admin.marketing.pixels.update_single') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                <input type="hidden" name="tracker_name" value="{{ \App\Helpers\LocalizationHelper::get('gtm_title') }}">

                <div class="space-y-1">
                    <label class="text-slate-300">{{ \App\Helpers\LocalizationHelper::get('gtm_id_label') }}</label>
                    <input type="text" name="gtm_id" value="{{ $settings['gtm_id'] ?? '' }}" placeholder="GTM-554S4282" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-bold focus:border-amber-400">
                    <p class="text-[10px] text-slate-500">{{ \App\Helpers\LocalizationHelper::get('gtm_id_help') }}</p>
                </div>

                <div class="flex items-center justify-between pt-8">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/40 font-cyber font-bold text-xs uppercase tracking-wider transition-all">
                        {{ \App\Helpers\LocalizationHelper::get('save_gtm_btn') }}
                    </button>

                    <a href="{{ route('admin.marketing.pixels.test', 'Google Tag Manager') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all">
                        {{ \App\Helpers\LocalizationHelper::get('test_btn') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- 3. Google Analytics 4 (GA4) Card -->
        @php
            $ga4Active = !isset($settings['ga4_active']) || $settings['ga4_active'] === '1';
        @endphp
        <div class="admin-glass rounded-3xl p-6 border {{ $ga4Active ? 'border-orange-500/40 bg-slate-900/60' : 'border-slate-800 bg-slate-950/60 opacity-80' }} space-y-5 transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-500/20 text-orange-400 border border-orange-500/30 flex items-center justify-center">
                        <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('ga4_title') }}</h3>
                        <span class="text-[10px] font-mono text-slate-400">{{ \App\Helpers\LocalizationHelper::get('ga4_sub') }}</span>
                    </div>
                </div>

                <form action="{{ route('admin.marketing.pixels.toggle', 'ga4_active') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-mono font-bold transition-all {{ $ga4Active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                        {{ $ga4Active ? \App\Helpers\LocalizationHelper::get('live_active') : \App\Helpers\LocalizationHelper::get('disabled') }}
                    </button>
                </form>
            </div>

            <form action="{{ route('admin.marketing.pixels.update_single') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                <input type="hidden" name="tracker_name" value="{{ \App\Helpers\LocalizationHelper::get('ga4_title') }}">

                <div class="space-y-1">
                    <label class="text-slate-300">{{ \App\Helpers\LocalizationHelper::get('ga4_id_label') }}</label>
                    <input type="text" name="ga4_id" value="{{ $settings['ga4_id'] ?? '' }}" placeholder="G-XXXXXXXXXX" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-bold focus:border-orange-400">
                    <p class="text-[10px] text-slate-500">{{ \App\Helpers\LocalizationHelper::get('ga4_id_help') }}</p>
                </div>

                <div class="flex items-center justify-between pt-8">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500/20 hover:bg-orange-500/30 text-orange-300 border border-orange-500/40 font-cyber font-bold text-xs uppercase tracking-wider transition-all">
                        {{ \App\Helpers\LocalizationHelper::get('save_ga4_btn') }}
                    </button>

                    <a href="{{ route('admin.marketing.pixels.test', 'Google Analytics 4') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all">
                        {{ \App\Helpers\LocalizationHelper::get('test_btn') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- 4. TikTok Pixel Card -->
        @php
            $tiktokActive = !isset($settings['tiktok_active']) || $settings['tiktok_active'] === '1';
        @endphp
        <div class="admin-glass rounded-3xl p-6 border {{ $tiktokActive ? 'border-pink-500/40 bg-slate-900/60' : 'border-slate-800 bg-slate-950/60 opacity-80' }} space-y-5 transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-500/20 text-pink-400 border border-pink-500/30 flex items-center justify-center">
                        <i data-lucide="video" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('tiktok_title') }}</h3>
                        <span class="text-[10px] font-mono text-slate-400">{{ \App\Helpers\LocalizationHelper::get('tiktok_sub') }}</span>
                    </div>
                </div>

                <form action="{{ route('admin.marketing.pixels.toggle', 'tiktok_active') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-mono font-bold transition-all {{ $tiktokActive ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                        {{ $tiktokActive ? \App\Helpers\LocalizationHelper::get('live_active') : \App\Helpers\LocalizationHelper::get('disabled') }}
                    </button>
                </form>
            </div>

            <form action="{{ route('admin.marketing.pixels.update_single') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                <input type="hidden" name="tracker_name" value="{{ \App\Helpers\LocalizationHelper::get('tiktok_title') }}">

                <div class="space-y-1">
                    <label class="text-slate-300">{{ \App\Helpers\LocalizationHelper::get('tiktok_id_label') }}</label>
                    <input type="text" name="tiktok_pixel_id" value="{{ $settings['tiktok_pixel_id'] ?? '' }}" placeholder="e.g. CXXXXXXXXXXXXXXX" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-bold focus:border-pink-400">
                    <p class="text-[10px] text-slate-500">{{ \App\Helpers\LocalizationHelper::get('tiktok_id_help') }}</p>
                </div>

                <div class="flex items-center justify-between pt-8">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-pink-500/20 hover:bg-pink-500/30 text-pink-300 border border-pink-500/40 font-cyber font-bold text-xs uppercase tracking-wider transition-all">
                        {{ \App\Helpers\LocalizationHelper::get('save_tiktok_btn') }}
                    </button>

                    <a href="{{ route('admin.marketing.pixels.test', 'TikTok Pixel') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all">
                        {{ \App\Helpers\LocalizationHelper::get('test_btn') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- 5. Custom Header & Footer Script Card -->
        @php
            $customActive = !isset($settings['custom_scripts_active']) || $settings['custom_scripts_active'] === '1';
        @endphp
        <div class="lg:col-span-2 admin-glass rounded-3xl p-6 border {{ $customActive ? 'border-emerald-500/40 bg-slate-900/60' : 'border-slate-800 bg-slate-950/60 opacity-80' }} space-y-5 transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center">
                        <i data-lucide="code" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">{{ \App\Helpers\LocalizationHelper::get('custom_scripts_title') }}</h3>
                        <span class="text-[10px] font-mono text-slate-400">{{ \App\Helpers\LocalizationHelper::get('custom_scripts_sub') }}</span>
                    </div>
                </div>

                <form action="{{ route('admin.marketing.pixels.toggle', 'custom_scripts_active') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-mono font-bold transition-all {{ $customActive ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                        {{ $customActive ? \App\Helpers\LocalizationHelper::get('live_active') : \App\Helpers\LocalizationHelper::get('disabled') }}
                    </button>
                </form>
            </div>

            <form action="{{ route('admin.marketing.pixels.update_single') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                <input type="hidden" name="tracker_name" value="{{ \App\Helpers\LocalizationHelper::get('custom_scripts_title') }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-slate-300">{{ \App\Helpers\LocalizationHelper::get('header_scripts_label') }}</label>
                        <textarea name="header_custom_code" rows="3" placeholder="<meta name='facebook-domain-verification' content='...' />" 
                                  class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-mono text-[11px]">{{ $settings['header_custom_code'] ?? '' }}</textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300">{{ \App\Helpers\LocalizationHelper::get('footer_scripts_label') }}</label>
                        <textarea name="footer_custom_code" rows="3" placeholder="<script>/* Live Chat Code */</script>" 
                                  class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-mono text-[11px]">{{ $settings['footer_custom_code'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 font-cyber font-bold text-xs uppercase tracking-wider transition-all">
                        {{ \App\Helpers\LocalizationHelper::get('save_custom_scripts_btn') }}
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Modal: Add New Tracking Tag -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showAddModal = false"></div>

        <div class="relative w-full max-w-lg bg-slate-900 border border-cyan-500/40 rounded-3xl p-6 sm:p-8 shadow-2xl z-10 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-base text-white">{{ \App\Helpers\LocalizationHelper::get('add_pixel_modal_title') }}</h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('admin.marketing.pixels.update_single') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                <input type="hidden" name="tracker_name" value="{{ \App\Helpers\LocalizationHelper::get('add_pixel_modal_title') }}">

                <div class="space-y-1">
                    <label class="text-slate-300">{{ \App\Helpers\LocalizationHelper::get('tracker_name_label') }}</label>
                    <input type="text" placeholder="e.g. Pinterest Pixel" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">{{ \App\Helpers\LocalizationHelper::get('script_code_label') }}</label>
                    <textarea name="header_custom_code" rows="4" required placeholder="<script>...</script>" 
                              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-mono text-[11px]">{{ $settings['header_custom_code'] ?? '' }}</textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    {{ \App\Helpers\LocalizationHelper::get('save_and_activate_btn') }}
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
