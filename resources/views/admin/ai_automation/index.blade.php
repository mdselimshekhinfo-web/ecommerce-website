@extends('layouts.admin')

@section('title', 'AI অটোমেশন ও বিজনেস কলিং হাব - Admin Panel')
@section('page-title', 'এন্টারপ্রাইজ এআই অটো-পাইলট ও মার্কেটিং হাব')

@section('content')
<div class="space-y-8" x-data="aiControlCenter()">
    
    <!-- Hero Header Banner with AI Status & Quick Switch -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-cyan-950/80 via-slate-900 to-indigo-950/80 border border-cyan-500/30 p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-mono font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                    <span>AI 3.0 Core Engine Active</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-cyber tracking-wide">
                    🤖 এআই সেলস, WhatsApp বিজনেস ও ভয়েস কলিং হাব
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    ২৪/৭ গ্রাহকের সাথে স্বাভাবিক বাংলায় চ্যাট, ভয়েস কলিং, WhatsApp অটো-কনফার্মেশন, ইনস্ট্যান্ট ফেসবুক অ্যাড কপি এবং এসইও অপ্টিমাইজেশন পরিচালনা করুন।
                </p>
            </div>

            <!-- Quick Auto-Pilot Switches -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                <form action="{{ route('admin.ai_automation.save_settings') }}" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <input type="hidden" name="ai_auto_dispatch_courier" value="{{ $autoDispatchStatus === '1' ? '0' : '1' }}">
                    <button type="submit" class="px-4 py-2.5 rounded-xl border text-xs font-bold font-mono transition-all flex items-center justify-between gap-3 shadow-lg {{ $autoDispatchStatus === '1' ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-300' : 'bg-slate-900 border-slate-700 text-slate-400' }}">
                        <span class="flex items-center gap-2">
                            <i data-lucide="zap" class="w-4 h-4 {{ $autoDispatchStatus === '1' ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                            <span>অটো-কুরিয়ার বুকিং:</span>
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $autoDispatchStatus === '1' ? 'bg-emerald-500 text-slate-950' : 'bg-slate-800 text-slate-400' }}">
                            {{ $autoDispatchStatus === '1' ? 'চালু আছে' : 'বন্ধ' }}
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Live Performance Metric Cards (4 Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-cyan-500/40 transition-all cursor-pointer" @click="activeTab = 'chat'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">এআই লাইভ চ্যাট সেশন</span>
                <i data-lucide="messages-square" class="w-4 h-4 text-cyan-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-white font-mono">{{ $aiConversationsCount }} টি</h3>
                <span class="text-[11px] text-cyan-400 font-bold">Auto-Pilot</span>
            </div>
            <p class="text-[11px] text-slate-500">বাংলা ও ইংরেজি ভয়েস + টেক্সট</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-purple-500/40 transition-all cursor-pointer" @click="activeTab = 'chat'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">এআই জেনারেটেড মোট সেলস</span>
                <i data-lucide="trending-up" class="w-4 h-4 text-purple-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-white font-mono">{{ \App\Helpers\BanglaHelper::formatTaka($aiRevenue) }}</h3>
                <span class="text-[11px] text-emerald-400 font-bold">{{ $aiConversionRate }}% কনভার্সন</span>
            </div>
            <p class="text-[11px] text-slate-500">মোট {{ $aiOrdersCount }}টি সফল অর্ডার কনফার্মড</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-emerald-500/40 transition-all cursor-pointer" @click="activeTab = 'whatsapp'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">WhatsApp ও ভয়েস ভেরিফাইড</span>
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-white font-mono">{{ $verifiedOrdersCount }} / {{ $totalOrders }}</h3>
                <span class="text-[11px] text-emerald-400 font-bold">100% Verified</span>
            </div>
            <p class="text-[11px] text-slate-500">স্বয়ংক্রিয় অর্ডার কনফার্মেশন ও বুকিং</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-pink-500/40 transition-all cursor-pointer" @click="activeTab = 'marketing'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">AI মার্কেটিং ও কপিরাইটিং</span>
                <i data-lucide="sparkles" class="w-4 h-4 text-pink-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-white font-mono">1-Click Ad AI</h3>
                <span class="text-[11px] text-pink-400 font-bold">FB & IG Ready</span>
            </div>
            <p class="text-[11px] text-slate-500">হাই-কনভার্টিং বিজ্ঞাপন ও ক্যাপশন</p>
        </div>

    </div>

    <!-- Clean Tabbed Interface -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-6">
        
        <!-- Tab Navigation Bar -->
        <div class="flex items-center gap-2 border-b border-slate-800 pb-4 overflow-x-auto select-none">
            <button type="button" @click="activeTab = 'chat'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'chat' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="bot" class="w-4 h-4"></i>
                <span>১. এআই সেলস চ্যাটবট ও পারসোনা</span>
            </button>

            <button type="button" @click="activeTab = 'whatsapp'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'whatsapp' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>২. WhatsApp বিজনেস গেটওয়ে ও অটো-চ্যাট</span>
            </button>

            <button type="button" @click="activeTab = 'voice'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'voice' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                <span>৩. বাংলা ভয়েস কলিং ও ডায়ালার স্টেশন</span>
            </button>

            <button type="button" @click="activeTab = 'marketing'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'marketing' ? 'bg-pink-500/20 text-pink-300 border border-pink-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="sparkles" class="w-4 h-4 text-pink-400"></i>
                <span>৪. 📢 AI সোশ্যাল অ্যাড ও মার্কেটিং কপি</span>
            </button>

            <button type="button" @click="activeTab = 'seo'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'seo' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>৫. গুগল অটো-এসইও ও সাইটম্যাপ</span>
            </button>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 1: এআই সেলস বট ও পারসোনা কনফিগারেশন -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'chat'" x-cloak class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left Form (7 Cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <form action="{{ route('admin.ai_automation.save_settings') }}" method="POST" class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-4">
                        @csrf
                        
                        <div class="flex items-center space-x-2 text-cyan-400 border-b border-slate-800 pb-3">
                            <i data-lucide="cpu" class="w-5 h-5"></i>
                            <h4 class="font-cyber font-bold text-sm text-white">এআই সেলস এজেন্টের চরিত্র ও আচরণ</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">এআই বটের নাম</label>
                                <input type="text" name="ai_bot_name" value="{{ $botName }}" placeholder="Aura AI"
                                       class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">কথা বলার ভঙ্গি (Tone)</label>
                                <select name="ai_bot_persona" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                                    <option value="polite_sales" {{ $botPersona === 'polite_sales' ? 'selected' : '' }}>🌸 বিনম্র সেলস এক্সপার্ট (Polite & Friendly)</option>
                                    <option value="tech_guru" {{ $botPersona === 'tech_guru' ? 'selected' : '' }}>⚡ টেক গুরু (Technical & Direct)</option>
                                    <option value="bargain_closer" {{ $botPersona === 'bargain_closer' ? 'selected' : '' }}>🎯 ডিসকাউন্ট ক্লোজার (Bargain & Fast Close)</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">কাস্টমারকে প্রথম সম্ভাষণ মেসেজ (Welcome Greeting)</label>
                            <textarea name="ai_bot_greeting" rows="3" 
                                      class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-cyan-400 leading-relaxed">{{ $botGreeting }}</textarea>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">সর্বোচ্চ অটো-ডিসকাউন্ট লিমিট (%)</label>
                            <div class="flex items-center space-x-3">
                                <input type="number" name="ai_auto_discount_limit" value="{{ $autoDiscountLimit }}" min="0" max="50"
                                       class="w-32 bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-cyan-300 font-mono font-bold focus:outline-none focus:border-cyan-400">
                                <span class="text-xs text-slate-400">কাস্টমার দরদাম করলে এআই সর্বোচ্চ এই পরিমাণ ছাড় দিতে পারবে।</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all flex items-center space-x-2 shadow-lg">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span>সেটিংস সংরক্ষণ করুন</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Live Preview (5 Cols) -->
                <div class="lg:col-span-5 p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                        <div class="flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                            <span class="text-xs font-bold text-white">রিয়েল-টাইম চ্যাট উইজেট লাইভ প্রিভিউ</span>
                        </div>
                        <span class="text-[10px] font-mono text-cyan-400">Voice + Text</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800 space-y-2.5 text-xs">
                        <div class="flex items-start space-x-2">
                            <div class="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-[10px] shrink-0">🤖</div>
                            <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 leading-relaxed text-[11px]">{{ $botGreeting }}</div>
                        </div>

                        <div class="flex items-start space-x-2 justify-end">
                            <div class="p-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-sky-500 text-slate-950 font-bold text-right text-[11px]">
                                ভাইয়া, AuraBlade ANC Pro ইয়ারবাডস নিতে চাই।
                            </div>
                            <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center text-[10px] text-slate-300 shrink-0">👤</div>
                        </div>

                        <div class="flex items-start space-x-2">
                            <div class="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-[10px] shrink-0">🤖</div>
                            <div class="p-2.5 rounded-xl bg-slate-950 border border-cyan-500/30 text-white space-y-1 text-[11px]">
                                <p>🎉 দুর্দান্ত পছন্দ! AuraBlade ANC Pro এর মূল্য মাত্র <b>৳২,৯৫০</b>।</p>
                                <p class="text-emerald-300 font-bold">অর্ডার সম্পন্ন করতে আপনার ফোন নম্বর ও ঠিকানা লিখুন!</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 2: WhatsApp বিজনেস গেটওয়ে ও অটো-চ্যাট -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'whatsapp'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: WhatsApp Business Connection & Template (6 Cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <form action="{{ route('admin.ai_automation.save_settings') }}" method="POST" class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-4">
                        @csrf
                        
                        <div class="flex items-center space-x-2 text-emerald-400 border-b border-slate-800 pb-3">
                            <i data-lucide="message-circle" class="w-5 h-5"></i>
                            <h4 class="font-cyber font-bold text-sm text-white">WhatsApp বিজনেস নম্বর ও ক্লাউড গেটওয়ে</h4>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">আপনার WhatsApp বিজনেস নম্বর</label>
                            <input type="text" name="whatsapp_business_phone" value="{{ $waBusinessPhone }}" placeholder="+8801947521688"
                                   class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-emerald-400 font-mono">
                            <p class="text-[10px] text-slate-500">এই নম্বর থেকে কাস্টমারদের স্বয়ংক্রিয় ভেরিফিকেশন ও মেসেজ পাঠানো হবে</p>
                        </div>

                        <!-- Meta Cloud API Config -->
                        <div class="p-3.5 rounded-xl bg-slate-950 border border-emerald-500/30 space-y-3">
                            <div class="flex items-center justify-between text-[11px] text-emerald-300 font-bold border-b border-slate-800 pb-1.5">
                                <span>📱 Meta WhatsApp Cloud API ক্রেডেনশিয়ালস</span>
                                <span class="text-emerald-400 text-[10px]">Cloud Active</span>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">Meta Phone Number ID</label>
                                <input type="text" name="whatsapp_phone_number_id" value="{{ $waPhoneNumberId }}" placeholder="1048291049281"
                                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-emerald-400 font-mono">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">Permanent Access Token (EAAB...)</label>
                                <input type="password" name="whatsapp_cloud_token" value="{{ $waCloudToken }}" placeholder="••••••••••••••••••••••••••••••••"
                                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-emerald-400 font-mono">
                            </div>

                            <!-- Webhook URL Details -->
                            <div class="pt-2 border-t border-slate-800 space-y-2 text-[11px]">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Webhook Callback URL:</span>
                                    <button type="button" @click="copyText('{{ url('/api/whatsapp/webhook') }}', 'Webhook URL কপি হয়েছে!')" class="text-emerald-400 font-mono font-bold hover:underline">কপি 📋</button>
                                </div>
                                <input type="text" readonly value="{{ url('/api/whatsapp/webhook') }}" class="w-full bg-slate-900/60 border border-slate-800 rounded-lg p-2 text-[10px] text-slate-400 font-mono">
                            </div>
                        </div>

                        <!-- Template Message -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">স্বয়ংক্রিয় ভেরিফিকেশন মেসেজ টেমপ্লেট</label>
                            <textarea name="ai_wa_template" rows="4" 
                                      class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-emerald-400 font-mono leading-relaxed">{{ $waTemplate }}</textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all flex items-center space-x-2 shadow-lg">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span>WhatsApp গেটওয়ে সেভ করুন</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Interactive WhatsApp Simulation Tool (6 Cols) -->
                <div class="lg:col-span-6 p-6 rounded-2xl bg-slate-950 border border-emerald-500/30 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                        <h4 class="font-cyber font-bold text-xs text-emerald-400 uppercase tracking-wider">
                            WhatsApp অটো-রিপ্লাই ও কুরিয়ার সিমুলেটর
                        </h4>
                        <span class="text-[10px] font-mono text-emerald-400">AI Webhook Test</span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="space-y-1">
                            <label class="text-slate-400 font-medium">কাস্টমারের ফোন নম্বর:</label>
                            <input type="text" x-model="dialPhone" placeholder="019XXXXXXXX" 
                                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-emerald-400 font-mono">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-400 font-medium">কাস্টমারের সম্ভাব্য রিপ্লাই:</label>
                            <div class="flex items-center space-x-2">
                                <input type="text" x-model="waInput" placeholder="যেমন: হ্যাঁ পাঠিয়ে দিন"
                                       class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-emerald-400">
                                <button type="button" @click="testWhatsAppReply()" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs transition-all shadow-lg">
                                    টেস্ট করুন
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1 flex-wrap">
                            <span class="text-[10px] text-slate-500">দ্রুত টেস্ট বাটন:</span>
                            <button type="button" @click="waInput = 'হ্যাঁ পাঠিয়ে দিন'; testWhatsAppReply()" class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[10px] text-slate-300 hover:text-white">"হ্যাঁ পাঠিয়ে দিন"</button>
                            <button type="button" @click="waInput = 'confirm'; testWhatsAppReply()" class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[10px] text-slate-300 hover:text-white">"confirm"</button>
                            <button type="button" @click="waInput = 'না বাতিল করুন'; testWhatsAppReply()" class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[10px] text-pink-400 hover:text-pink-300">"না বাতিল"</button>
                        </div>

                        <!-- Result Box -->
                        <template x-if="waResult">
                            <div class="p-3.5 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs space-y-1 mt-3">
                                <p class="font-bold flex items-center gap-1.5">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                                    <span>অ্যাকশন সম্পন্ন: <b x-text="waResult.action || 'Order Processed'"></b></span>
                                </p>
                                <p class="text-[11px] text-slate-300 whitespace-pre-line" x-text="waResult.reply"></p>
                                <template x-if="waResult.tracking_code">
                                    <p class="text-[10px] font-mono text-cyan-300 pt-1" x-text="'Steadfast Tracking: #' + waResult.tracking_code"></p>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 3: বাংলা ভয়েস কলিং ও ডায়ালার স্টেশন -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'voice'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: Telecom & BD IP TSP Gateway Configuration (6 Cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <form action="{{ route('admin.ai_automation.save_settings') }}" method="POST" class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-4" x-data="{ provider: '{{ $voiceProvider }}' }">
                        @csrf
                        
                        <div class="flex items-center space-x-2 text-purple-400 border-b border-slate-800 pb-3">
                            <i data-lucide="radio-tower" class="w-5 h-5"></i>
                            <h4 class="font-cyber font-bold text-sm text-white">টেলিকম ও বিডি আইপি গেটওয়ে কনফিগারেশন</h4>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">ভয়েস গেটওয়ে প্রোভাইডার নির্বাচন করুন</label>
                            <select name="voice_gateway_provider" x-model="provider" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-purple-400">
                                <option value="alaap_bd_ip">🇧🇩 BTCL আলাপের আইপি নাম্বার (09696xxxxxx)</option>
                                <option value="amberit_sip">🇧🇩 Amber IT / Brilliant Connect SIP Trunk (096xxxxxxx)</option>
                                <option value="twilio">🌍 Twilio International Cloud Voice</option>
                                <option value="browser_tts">💻 ব্রাউজার বিল্ট-ইন বাংলা ভয়েস ইঞ্জিন (SpeechSynthesis)</option>
                            </select>
                        </div>

                        <!-- BD IP TSP Settings (Alaap & Amber IT) -->
                        <div class="space-y-3 p-3.5 rounded-xl bg-slate-950 border border-purple-500/30" x-show="provider === 'alaap_bd_ip' || provider === 'amberit_sip'">
                            <div class="flex items-center justify-between text-[11px] text-purple-300 font-bold border-b border-slate-800 pb-1.5">
                                <span>🇧🇩 বিডি আইপি টেলিফোনি (096xx) অ্যাকাউন্ট ক্রেডেনশিয়ালস</span>
                                <span class="text-emerald-400 text-[10px]">Active SIP</span>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">আপনার আলাপের আইপি / কলার আইডি নাম্বার</label>
                                <input type="text" name="bd_ip_number" value="{{ $bdIpNumber }}" placeholder="09696123456 বা 09638123456"
                                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-purple-400 font-mono">
                                <p class="text-[10px] text-slate-500">গ্রাহকের ফোনে এই নাম্বারটি কলার আইডি হিসেবে শো করবে</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-300">SIP সার্ভার হোস্ট</label>
                                    <input type="text" name="sip_server_host" value="{{ $sipServerHost }}" placeholder="sip.amberit.com.bd"
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-purple-400 font-mono">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-300">SIP ইউজারনেম</label>
                                    <input type="text" name="sip_username" value="{{ $sipUsername }}" placeholder="8809696XXXXXX"
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-purple-400 font-mono">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-300">SIP সিক্রেট / পাসওয়ার্ড</label>
                                    <input type="password" name="sip_password" value="{{ $sipPassword }}" placeholder="••••••••••••"
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-purple-400 font-mono">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-300">REST Voice API Key (ঐচ্ছিক)</label>
                                    <input type="password" name="sip_api_key" value="{{ $sipApiKey }}" placeholder="••••••••••••"
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-purple-400 font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Twilio Settings -->
                        <div class="space-y-3 p-3.5 rounded-xl bg-slate-950 border border-purple-500/30" x-show="provider === 'twilio'">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">Twilio Account SID</label>
                                <input type="text" name="twilio_account_sid" value="{{ $twilioSid }}" placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
                                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-purple-400 font-mono">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">Twilio Auth Token</label>
                                <input type="password" name="twilio_auth_token" value="{{ $twilioToken }}" placeholder="••••••••••••••••••••••••••••••••"
                                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-purple-400 font-mono">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">Twilio Caller ID</label>
                                <input type="text" name="twilio_phone_number" value="{{ $twilioFrom }}" placeholder="+1234567890"
                                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-purple-400 font-mono">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center space-x-2 shadow-lg">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span>গেটওয়ে কনফিগারেশন সেভ করুন</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Interactive In-Browser Softphone & Virtual Keypad (6 Cols) -->
                <div class="lg:col-span-6 p-6 rounded-2xl bg-slate-950 border border-purple-500/30 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                        <div class="flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                            <h4 class="font-cyber font-bold text-xs text-purple-300 uppercase tracking-wider">
                                ভার্চুয়াল ওয়েব ডায়ালার ও লাইভ ভয়েস স্টেশন
                            </h4>
                        </div>
                        <span class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/30">WebRTC Softphone</span>
                    </div>

                    <!-- Recent Orders Quick-Picker -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-300">সাম্প্রতিক অর্ডার থেকে কাস্টমার সিলেক্ট করুন:</label>
                        <select @change="dialPhone = $event.target.value" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-purple-300 focus:outline-none focus:border-purple-400 font-mono">
                            <option value="01947521688">01947521688 (তানভীর আহমেদ - ৳৩,০১০)</option>
                            @foreach($recentOrders as $ro)
                                <option value="{{ $ro->customer_phone }}">{{ $ro->customer_phone }} ({{ $ro->customer_name }} - {{ \App\Helpers\BanglaHelper::formatTaka($ro->total_amount) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Phone Number Display Screen -->
                    <div class="p-3.5 rounded-2xl bg-slate-900 border border-purple-500/40 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono text-slate-500 uppercase">CALLING TO:</span>
                            <span class="text-[10px] font-mono text-purple-400">Caller: {{ $bdIpNumber ?: '+8809678831374' }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="text" x-model="dialPhone" placeholder="019XXXXXXXX"
                                   class="flex-1 bg-transparent text-lg sm:text-xl font-bold font-mono text-white tracking-widest focus:outline-none">
                            <button type="button" @click="dialPhone = dialPhone.slice(0, -1)" class="p-1.5 text-slate-400 hover:text-red-400 text-xs font-mono" title="মুছে ফেলুন">
                                ⌫
                            </button>
                        </div>
                    </div>

                    <!-- Softphone Keypad (3x4 Grid) -->
                    <div class="grid grid-cols-3 gap-2 py-1">
                        @foreach(['1', '2', '3', '4', '5', '6', '7', '8', '9', '*', '0', '#'] as $key)
                            <button type="button" @click="dialPhone += '{{ $key }}'" 
                                    class="py-2.5 rounded-xl bg-slate-900 hover:bg-purple-950/60 border border-slate-800 hover:border-purple-500/40 text-slate-200 hover:text-white font-mono font-bold text-sm transition-all shadow-sm active:scale-95">
                                {{ $key }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Action Call Buttons (Tri-Action Bar) -->
                    <div class="grid grid-cols-3 gap-2 pt-1">
                        <button type="button" @click="dialCustomVoiceCall()" 
                                :disabled="calling"
                                class="py-3 rounded-xl bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-400 hover:to-pink-500 text-white font-bold text-xs uppercase flex items-center justify-center space-x-1.5 shadow-lg disabled:opacity-50 transition-all">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            <span x-text="calling ? 'কল...' : 'AI কল 📞'"></span>
                        </button>

                        <button type="button" @click="
                            navigator.clipboard.writeText(dialPhone);
                            alert('📞 ফোন নম্বর (' + dialPhone + ') কপি করা হয়েছে!\nআপনার Dial App বা ফোনে পেস্ট করে কল করুন।');
                            window.location.href = 'tel:' + dialPhone;
                        " class="py-3 rounded-xl bg-slate-900 border border-purple-500/40 text-purple-300 hover:bg-purple-600 hover:text-white font-bold text-xs flex items-center justify-center space-x-1.5 transition-all" title="Dial App এ সরাসরি ডায়াল">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            <span>Dial App</span>
                        </button>

                        <a :href="'https://api.whatsapp.com/send?phone=88' + dialPhone.replace(/^88/, '').replace(/^0+/, '')" target="_blank"
                           class="py-3 rounded-xl bg-emerald-600/30 text-emerald-300 border border-emerald-500/40 hover:bg-emerald-600 hover:text-white font-bold text-xs flex items-center justify-center space-x-1.5 transition-all text-center">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span>WhatsApp</span>
                        </a>
                    </div>

                    <!-- Active Connected Screen & Waveform -->
                    <div x-show="callActive" x-cloak class="p-4 rounded-2xl bg-purple-950/80 border border-purple-400/60 space-y-3">
                        <div class="flex items-center justify-between text-purple-200">
                            <span class="font-bold flex items-center gap-2 text-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>📞 কল চলমান: <b x-text="dialPhone" class="font-mono text-white"></b></span>
                            </span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-bold font-mono">00:18 CONNECTED</span>
                        </div>

                        <!-- Live Waveform Animation -->
                        <div class="flex items-center justify-center space-x-1 py-2">
                            <span class="w-1 h-3 bg-purple-400 rounded-full animate-bounce"></span>
                            <span class="w-1 h-6 bg-pink-400 rounded-full animate-bounce [animation-delay:0.1s]"></span>
                            <span class="w-1 h-8 bg-purple-300 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                            <span class="w-1 h-10 bg-emerald-400 rounded-full animate-bounce [animation-delay:0.3s]"></span>
                            <span class="w-1 h-6 bg-cyan-400 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                            <span class="w-1 h-3 bg-purple-400 rounded-full animate-bounce [animation-delay:0.5s]"></span>
                        </div>
                        
                        <div class="p-3 rounded-xl bg-slate-950/90 border border-purple-800/60 text-xs text-slate-200 leading-relaxed italic" x-text="currentVoiceScript"></div>
                        
                        <div class="pt-2 flex items-center gap-2">
                            <button type="button" @click="confirmVoiceResponse('হ্যাঁ আমি অর্ডারটি কনফার্ম করছি')" 
                                    class="flex-1 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs transition-all shadow-lg flex items-center justify-center space-x-1.5">
                                <span>🗣️ বলুন: "হ্যাঁ নিব" (কনফার্ম)</span>
                            </button>
                            <button type="button" @click="confirmVoiceResponse('না বাতিল করুন')" 
                                    class="px-4 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs transition-all flex items-center justify-center space-x-1">
                                <span>❌ "বাতিল"</span>
                            </button>
                        </div>
                    </div>

                    <!-- Call Completed Result -->
                    <template x-if="voiceResult">
                        <div class="p-4 rounded-2xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs space-y-2">
                            <p class="font-bold flex items-center gap-1.5 text-sm">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                                <span>ভয়েস কল সম্পন্ন: অর্ডারটি কনফার্ম ও অটো-বুক হয়েছে!</span>
                            </p>
                            <p class="text-xs text-slate-300" x-text="voiceResult.ai_voice_reply"></p>
                        </div>
                    </template>
                </div>

            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 4: AI সোশ্যাল অ্যাড ও মার্কেটিং কপি জেনারেটর -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'marketing'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: Product & Tone Selector (4 Cols) -->
                <div class="lg:col-span-4 p-6 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-4">
                    <div class="flex items-center space-x-2 text-pink-400 border-b border-slate-800 pb-3">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                        <h4 class="font-cyber font-bold text-sm text-white">AI অ্যাড ও কপি জেনারেটর</h4>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">প্রোডাক্ট নির্বাচন করুন</label>
                        <select x-model="selectedProductId" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-pink-400">
                            @foreach($sampleProducts as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->name }} ({{ \App\Helpers\BanglaHelper::formatTaka($sp->final_price) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">বিজ্ঞাপনের টোন ও ভঙ্গি</label>
                        <select x-model="adTone" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-pink-400">
                            <option value="sales_boost">🔥 হাই-কনভার্টিং সেলস ধামাকা (উচ্চ বিক্রি)</option>
                            <option value="premium">💎 প্রিমিয়াম ও লাক্সারি লাইফস্টাইল</option>
                            <option value="urgency">⚡ সীমিত স্টক ও ফ্ল্যাশ ডিল আরজেন্সি</option>
                        </select>
                    </div>

                    <button type="button" @click="generateAdCopy()" 
                            :disabled="generatingAd"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-600 hover:from-pink-400 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg disabled:opacity-50 transition-all">
                        <i data-lucide="wand-2" class="w-4 h-4"></i>
                        <span x-text="generatingAd ? 'এআই কপি জেনারেট হচ্ছে...' : '⚡ ১-ক্লিকে AI অ্যাড কপি তৈরি করুন'"></span>
                    </button>
                </div>

                <!-- Right: High-Converting Ad Post Cards (8 Cols) -->
                <div class="lg:col-span-8 space-y-4">
                    
                    <!-- 1. Facebook Ad Copy Card -->
                    <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                            <span class="text-xs font-bold text-blue-400 flex items-center gap-1.5">
                                <i data-lucide="facebook" class="w-4 h-4"></i>
                                <span>Facebook হাই-কনভার্টিং অ্যাড পোস্ট</span>
                            </span>
                            <button type="button" @click="copyText(marketingData.facebook_ad_copy, 'Facebook অ্যাড কপি কপি হয়েছে!')"
                                    class="px-3 py-1 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-400 hover:bg-blue-500 hover:text-white text-[11px] font-mono font-bold transition-all flex items-center space-x-1">
                                <i data-lucide="copy" class="w-3 h-3"></i>
                                <span>কপি করুন</span>
                            </button>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 text-xs text-slate-200 whitespace-pre-line font-sans leading-relaxed" x-text="marketingData.facebook_ad_copy"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- 2. Instagram Caption Card -->
                        <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2.5">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                                <span class="text-xs font-bold text-pink-400 flex items-center gap-1.5">
                                    <i data-lucide="instagram" class="w-3.5 h-3.5"></i>
                                    <span>Instagram ক্যাপশন ও হ্যাশট্যাগ</span>
                                </span>
                                <button type="button" @click="copyText(marketingData.instagram_caption, 'Instagram ক্যাপশন কপি হয়েছে!')"
                                        class="px-2.5 py-0.5 rounded-lg bg-pink-500/10 text-pink-400 text-[10px] font-mono font-bold hover:bg-pink-500 hover:text-white transition-all">
                                    কপি
                                </button>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 text-[11px] text-slate-300 whitespace-pre-line font-sans" x-text="marketingData.instagram_caption"></div>
                        </div>

                        <!-- 3. SMS / WhatsApp Broadcast Card -->
                        <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2.5">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                                <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5">
                                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                    <span>SMS / WhatsApp ব্রডকাস্ট</span>
                                </span>
                                <button type="button" @click="copyText(marketingData.sms_marketing_copy, 'SMS ব্রডকাস্ট কপি হয়েছে!')"
                                        class="px-2.5 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-[10px] font-mono font-bold hover:bg-emerald-500 hover:text-white transition-all">
                                    কপি
                                </button>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 text-[11px] text-slate-300 whitespace-pre-line font-sans" x-text="marketingData.sms_marketing_copy"></div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 5: গুগল অটো-এসইও ও সাইটম্যাপ -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'seo'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: Auto SEO Generator (6 Cols) -->
                <div class="lg:col-span-6 p-6 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-4">
                    <div class="flex items-center space-x-2 text-amber-400 border-b border-slate-800 pb-3">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                        <h4 class="font-cyber font-bold text-sm text-white">Google Auto-SEO & Structured Schema</h4>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        আপনার স্টোরের প্রতিটি প্রোডাক্টের জন্য স্বয়ংক্রিয়ভাবে গুগল সার্চ ফ্রেন্ডলি মেটা টাইটেল, বাংলা ও ইংরেজি ডেসক্রিপশন এবং JSON-LD Rich Snippet Schema তৈরি করে রাখা হয়। এতে গুগলে সার্চ করলে সরাসরি স্টার রেটিং, দাম ও ইন-স্টক স্ট্যাটাস শো করে।
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="{{ route('sitemap') }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-950 border border-slate-700 hover:border-cyan-400 text-xs font-mono text-cyan-300 transition-all flex items-center space-x-1.5">
                            <i data-lucide="file-code" class="w-3.5 h-3.5"></i>
                            <span>sitemap.xml দেখুন ↗</span>
                        </a>
                    </div>

                    <form action="{{ route('admin.ai_automation.generate_seo') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-pink-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg transition-all">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            <span>সব পণ্যের এসইও স্কিমা রি-জেনারেট করুন (1-Click)</span>
                        </button>
                    </form>
                </div>

                <!-- Right: Google Search Card Mockup (6 Cols) -->
                <div class="lg:col-span-6 p-6 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800 text-xs">
                        <span class="font-bold text-white flex items-center gap-1.5"><i data-lucide="search" class="w-3.5 h-3.5 text-cyan-400"></i> গুগল সার্চ প্রিভিউ</span>
                        <span class="text-[10px] font-mono text-emerald-400">Rich Snippet Validated</span>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 space-y-1 font-sans">
                        <div class="flex items-center space-x-2 text-[11px] text-slate-400">
                            <span class="text-cyan-400">https://nexusdokan.bd</span>
                            <span>› product › earbuds-pro</span>
                        </div>
                        <h5 class="text-sm font-semibold text-blue-400 hover:underline cursor-pointer">
                            AuraBlade ANC Cyber Earbuds Pro - Buy in BD | ৳২,৯৫০
                        </h5>
                        <div class="flex items-center space-x-1 text-[11px] text-amber-400">
                            <span>★★★★★</span>
                            <span class="text-slate-400">রেটিং: 5.0 • ৳২,৯৫০ • স্টকে আছে • ২৪ ঘণ্টার ডেলিভারি</span>
                        </div>
                        <p class="text-xs text-slate-300 leading-snug">
                            বাংলাদেশে অরিজিনাল AuraBlade ANC Cyber Earbuds Pro কিনুন সেরা মূল্যে। অফিশিয়াল রিপ্লেসমেন্ট ওয়ারেন্টি, ক্যাশ অন ডেলিভারি এবং বিকাশ পেমেন্ট সুবিধা।
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function aiControlCenter() {
        return {
            activeTab: 'chat',
            waInput: 'হ্যাঁ পাঠিয়ে দিন',
            waResult: null,
            dialPhone: '01947521688',
            calling: false,
            callActive: false,
            currentVoiceScript: '',
            voiceResult: null,
            selectedProductId: '{{ $sampleProducts->first() ? $sampleProducts->first()->id : 1 }}',
            adTone: 'sales_boost',
            generatingAd: false,
            marketingData: {!! json_encode($sampleMarketingCopy ?? [
                'facebook_ad_copy' => "🔥 সীমিত সময়ের জন্য স্পেশাল অফার! প্রিমিয়াম AuraBlade ANC Pro ইয়ারবাডস এখন সেরা মূল্যে!\n\n✨ একটিভ নয়েজ ক্যান্সেলেশন\n🚀 সারা দেশে দ্রুত হোম ডেলিভারি\n💵 ক্যাশ অন ডেলিভারি সুবিধা\n\n👉 অর্ডার করতে ইনবক্স করুন বা ওয়েবসাইট ভিজিট করুন!\n📞 হটলাইন: +8809678831374",
                'instagram_caption' => "Upgrade your sound with AuraBlade ANC Pro! 🎧✨\n\n🛍️ Cash on Delivery Available.\n#NexusDokan #EarbudsBD #GadgetsBD",
                'sms_marketing_copy' => "স্পেশাল অফার! AuraBlade ANC Pro এখন মাত্র ৳২,৯৫০ টাকায়! আজই অর্ডার করুন: https://nexusdokan.bd/product/earbuds-pro"
            ]) !!},

            copyText(text, msg) {
                if (!text) return;
                navigator.clipboard.writeText(text);
                alert('✓ ' + (msg || 'কপি করা হয়েছে!'));
            },

            generateAdCopy() {
                this.generatingAd = true;
                fetch('{{ route("admin.ai_automation.generate_marketing_copy") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: this.selectedProductId,
                        tone: this.adTone
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.generatingAd = false;
                    this.marketingData = data;
                })
                .catch(() => {
                    this.generatingAd = false;
                });
            },

            testWhatsAppReply() {
                const text = this.waInput.trim();
                if (!text) return;

                fetch('{{ route("admin.ai_automation.simulate_whatsapp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: this.dialPhone, reply: text })
                })
                .then(res => res.json())
                .then(data => {
                    this.waResult = data;
                });
            },

            dialCustomVoiceCall() {
                this.calling = true;
                this.voiceResult = null;

                fetch('{{ route("admin.ai_automation.dial_voice") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: this.dialPhone })
                })
                .then(res => res.json())
                .then(data => {
                    this.calling = false;
                    this.callActive = true;
                    this.currentVoiceScript = data.voice_script;

                    // Speak Bengali Voice Script aloud using browser SpeechSynthesis (TTS)
                    if ('speechSynthesis' in window) {
                        const utterance = new SpeechSynthesisUtterance(data.voice_script);
                        utterance.lang = 'bn-BD';
                        utterance.rate = 0.95;
                        window.speechSynthesis.speak(utterance);
                    }
                })
                .catch(() => {
                    this.calling = false;
                });
            },

            confirmVoiceResponse(customerVoice) {
                this.callActive = false;
                if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel();
                }

                // Simulate order voice processing
                fetch('/admin/ai-automation/1/simulate-voice', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ voice_input: customerVoice })
                })
                .then(res => res.json())
                .then(data => {
                    this.voiceResult = data;
                    if ('speechSynthesis' in window && data.ai_voice_reply) {
                        const confirmUtterance = new SpeechSynthesisUtterance(data.ai_voice_reply);
                        confirmUtterance.lang = 'bn-BD';
                        window.speechSynthesis.speak(confirmUtterance);
                    }
                });
            }
        }
    }
</script>
@endpush
