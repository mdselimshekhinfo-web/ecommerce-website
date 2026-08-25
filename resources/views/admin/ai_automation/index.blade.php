@extends('layouts.admin')

@section('page-title', 'Enterprise AI অটোমেশন কন্ট্রোল সেন্টার')

@section('content')
<div class="space-y-6" x-data="aiControlCenter()">

    <!-- Top Executive Header Banner -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-purple-500/30 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 right-1/3 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-center space-x-4 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-purple-500 via-indigo-600 to-pink-500 p-0.5 shadow-lg flex items-center justify-center">
                <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                    <i data-lucide="sparkles" class="w-7 h-7 text-purple-400 animate-pulse"></i>
                </div>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="font-cyber font-black text-xl text-white tracking-wide">
                        Enterprise AI কমান্ড ও অটোমেশন হাব
                    </h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[11px] font-mono font-bold flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>AI Core 2.0 Active</span>
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1 max-w-xl">
                    রিয়েল-টাইম সেলস চ্যাটবট, WhatsApp ১-ক্লিক ভেরিফিকেশন, বাংলা ভয়েস কল এবং গুগল অটো-এসইও সম্পূর্ণ স্বয়ংক্রিয়ভাবে নিয়ন্ত্রণ করুন।
                </p>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex items-center gap-3 relative z-10">
            <a href="{{ route('admin.live_chat.index') }}" 
               class="px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-cyan-400 text-xs font-bold text-slate-200 hover:text-cyan-300 transition-all flex items-center space-x-2 shadow-sm">
                <i data-lucide="messages-square" class="w-4 h-4 text-cyan-400"></i>
                <span>লাইভ সাপোর্ট ডেস্ক ↗</span>
            </a>
            
            <form action="{{ route('admin.ai_automation.generate_seo') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 via-pink-500 to-indigo-600 text-white font-bold text-xs uppercase tracking-wider flex items-center space-x-2 shadow-lg hover:opacity-95 hover:scale-[1.02] transition-all">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    <span>১-ক্লিকে এসইও সিঙ্ক</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Real-Time AI Performance & Analytics Suite (4 KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-cyan-500/40 transition-all cursor-pointer" @click="activeTab = 'chat'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">এআই কথোপকথন সেশন</span>
                <i data-lucide="messages-square" class="w-4 h-4 text-cyan-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-white font-mono">{{ $aiConversationsCount }} টি চ্যাট</h3>
                <span class="text-[11px] text-cyan-400 font-bold">Live Pulse</span>
            </div>
            <p class="text-[11px] text-slate-500">গ্রাহকদের সাথে স্বয়ংক্রিয় কথোপকথন</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-emerald-500/40 transition-all cursor-pointer" @click="activeTab = 'chat'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">এআই জেনারেটেড সেলস</span>
                <i data-lucide="shopping-bag" class="w-4 h-4 text-emerald-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-emerald-400 font-mono">{{ \App\Helpers\BanglaHelper::formatTaka($aiRevenue) }}</h3>
                <span class="text-[11px] text-emerald-400 font-bold">{{ $aiOrdersCount }} টি অর্ডার</span>
            </div>
            <p class="text-[11px] text-slate-500">চ্যাটের মাধ্যমে সরাসরি কনফার্মড সেলস</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-purple-500/40 transition-all cursor-pointer" @click="activeTab = 'whatsapp'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">WhatsApp ও ভয়েস ভেরিফাইড</span>
                <i data-lucide="shield-check" class="w-4 h-4 text-purple-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-purple-300 font-mono">{{ $verifiedOrdersCount }} টি অর্ডার</h3>
                <span class="text-[11px] text-purple-400 font-bold">Zero Return Risk</span>
            </div>
            <p class="text-[11px] text-slate-500">১-ক্লিক ভেরিফিকেশন ও অটো-কুরিয়ার বুকিং</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-amber-500/40 transition-all cursor-pointer" @click="activeTab = 'seo'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">গুগল এসইও অপ্টিমাইজড</span>
                <i data-lucide="globe" class="w-4 h-4 text-amber-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-white font-mono">{{ $seoOptimizedCount }} / {{ $productsCount }} পণ্য</h3>
                <span class="text-[11px] text-amber-400 font-bold">100% Ready</span>
            </div>
            <p class="text-[11px] text-slate-500">Google Rich Snippets & Structured Schema</p>
        </div>

    </div>

    <!-- Clean Tabbed Interface (Organized & Powerful) -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-6">
        
        <!-- Tab Navigation Bar -->
        <div class="flex items-center gap-2 border-b border-slate-800 pb-4 overflow-x-auto select-none">
            <button type="button" @click="activeTab = 'chat'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'chat' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="bot" class="w-4 h-4"></i>
                <span>১. এআই সেলস চ্যাটবট ও পারসোনা (Auto-Pilot)</span>
            </button>

            <button type="button" @click="activeTab = 'whatsapp'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'whatsapp' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>২. WhatsApp ভেরিফিকেশন ও টেমপ্লেট বিল্ডার</span>
            </button>

            <button type="button" @click="activeTab = 'voice'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'voice' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                <span>৩. বাংলা ভয়েস কলিং ও টেলিকম গেটওয়ে</span>
            </button>

            <button type="button" @click="activeTab = 'seo'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'seo' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>৪. গুগল অটো-এসইও ও সাইটম্যাপ</span>
            </button>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 1: এআই সেলস চ্যাটবট ও পারসোনা কনফিগারেশন -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'chat'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: AI Configuration Form (7 Cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <form action="{{ route('admin.ai_automation.save_settings') }}" method="POST" class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-4">
                        @csrf
                        
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <h4 class="font-cyber font-bold text-sm text-white flex items-center gap-2">
                                <i data-lucide="sliders" class="w-4 h-4 text-cyan-400"></i>
                                <span>এআই অ্যাসিস্ট্যান্ট কনফিগারেশন ও পারসোনা</span>
                            </h4>
                            <span class="text-[11px] font-mono text-cyan-400">Live Auto-Pilot</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">এআই বট নাম</label>
                                <input type="text" name="ai_bot_name" value="{{ $botName }}" required
                                       class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">কথা বলার ধরন (Persona Tone)</label>
                                <select name="ai_bot_persona" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                                    <option value="polite_sales" {{ $botPersona === 'polite_sales' ? 'selected' : '' }}>বিনম্র ও প্রফেশনাল সেলস কনসালটেন্ট</option>
                                    <option value="tech_guru" {{ $botPersona === 'tech_guru' ? 'selected' : '' }}>সাইবার গ্যাজেট ও টেক গুরু</option>
                                    <option value="fast_direct" {{ $botPersona === 'fast_direct' ? 'selected' : '' }}>সরাসরি ও ফাস্ট অর্ডার টেকার</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">স্বাগতম বার্তা (Greeting Message)</label>
                            <textarea name="ai_bot_greeting" rows="2" 
                                      class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-cyan-400 leading-relaxed">{{ $botGreeting }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-300">সর্বোচ্চ এআই ডিসকাউন্ট পারমিশন (%)</label>
                                <input type="number" name="ai_auto_discount_limit" value="{{ $autoDiscountLimit }}" min="0" max="50"
                                       class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
                                <p class="text-[10px] text-slate-500">দরকষাকষি করলে এআই সর্বোচ্চ এই পরিমাণ ছাড় অফার করতে পারবে</p>
                            </div>

                            <div class="space-y-1.5 pt-2">
                                <label class="flex items-center space-x-2 text-xs font-bold text-emerald-400 cursor-pointer">
                                    <input type="checkbox" name="ai_auto_dispatch_courier" value="1" {{ $autoDispatchStatus === '1' ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-0">
                                    <span>অর্ডার কনফার্ম হলেই অটো-কুরিয়ার বুকিং</span>
                                </label>
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

                <!-- Right: Live Interactive Storefront Preview (5 Cols) -->
                <div class="lg:col-span-5 p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                        <div class="flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                            <span class="text-xs font-bold text-white">রিয়েল-টাইম চ্যাট উইজেট লাইভ টেস্ট</span>
                        </div>
                        <span class="text-[10px] font-mono text-cyan-400">Voice + Text</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800 space-y-2.5 text-xs">
                        <div class="flex items-start space-x-2">
                            <div class="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-[10px] shrink-0">🤖</div>
                            <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 leading-relaxed text-[11px]" x-text="'{{ addslashes($botGreeting) }}'"></div>
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
        <!-- TAB 2: WhatsApp ভেরিফিকেশন ও টেমপ্লেট বিল্ডার -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'whatsapp'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: Custom WhatsApp Template Builder (6 Cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <form action="{{ route('admin.ai_automation.save_settings') }}" method="POST" class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-4">
                        @csrf
                        
                        <div class="flex items-center space-x-2 text-emerald-400 border-b border-slate-800 pb-3">
                            <i data-lucide="message-circle" class="w-5 h-5"></i>
                            <h4 class="font-cyber font-bold text-sm text-white">WhatsApp মেসেজ টেমপ্লেট কাস্টমাইজার</h4>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">মেসেজ টেক্সট টেমপ্লেট</label>
                            <textarea name="ai_wa_template" rows="5" 
                                      class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-emerald-400 font-mono leading-relaxed">{{ $waTemplate }}</textarea>
                        </div>

                        <!-- Dynamic Tag Badges -->
                        <div class="space-y-1.5">
                            <span class="text-[11px] text-slate-400 font-medium">ক্লিক করে টেমপ্লেটে ডায়নামিক ট্যাগ যুক্ত করুন:</span>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="px-2 py-1 rounded-md bg-slate-950 border border-slate-800 text-[10px] font-mono text-cyan-300">{customer_name}</span>
                                <span class="px-2 py-1 rounded-md bg-slate-950 border border-slate-800 text-[10px] font-mono text-cyan-300">{product_name}</span>
                                <span class="px-2 py-1 rounded-md bg-slate-950 border border-slate-800 text-[10px] font-mono text-cyan-300">{total_amount}</span>
                                <span class="px-2 py-1 rounded-md bg-slate-950 border border-slate-800 text-[10px] font-mono text-cyan-300">{order_number}</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all flex items-center space-x-2 shadow-lg">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span>টেমপ্লেট সেভ করুন</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Interactive WhatsApp Simulation Tool (6 Cols) -->
                <div class="lg:col-span-6 p-6 rounded-2xl bg-slate-950 border border-emerald-500/30 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                        <h4 class="font-cyber font-bold text-xs text-emerald-400 uppercase tracking-wider">
                            WhatsApp রিপ্লাই ও অটো-কুরিয়ার সিমুলেটর
                        </h4>
                        <span class="text-[10px] font-mono text-slate-500">1-Click Dispatch</span>
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
                            <button type="button" @click="waInput = 'হ্যাঁ পাঠিয়ে দিন'; testWhatsAppReply()" class="px-2 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[10px] text-slate-300 hover:text-white">"হ্যাঁ পাঠিয়ে দিন"</button>
                            <button type="button" @click="waInput = 'confirm'; testWhatsAppReply()" class="px-2 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[10px] text-slate-300 hover:text-white">"confirm"</button>
                            <button type="button" @click="waInput = 'না বাতিল করুন'; testWhatsAppReply()" class="px-2 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[10px] text-pink-400 hover:text-pink-300">"না বাতিল"</button>
                        </div>

                        <!-- Result Box -->
                        <template x-if="waResult">
                            <div class="p-3.5 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs space-y-1 mt-3">
                                <p class="font-bold flex items-center gap-1.5">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                                    <span>অ্যাকশন সম্পন্ন: <b x-text="waResult.action || 'Order Processed'"></b></span>
                                </p>
                                <p class="text-[11px] text-slate-300" x-text="waResult.reply"></p>
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
        <!-- TAB 3: বাংলা ভয়েস কলিং ও টেলিকম গেটওয়ে -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'voice'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
        <!-- ================================================================= -->
        <!-- TAB 3: বাংলা ভয়েস কলিং ও বিডি আইপি টেলিফোনি (Alaap / 096 IP TSP) -->
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

                <!-- Right: Live Audio Dispatcher & Dialer (6 Cols) -->
                <div class="lg:col-span-6 p-6 rounded-2xl bg-slate-950 border border-purple-500/30 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                        <h4 class="font-cyber font-bold text-xs text-purple-400 uppercase tracking-wider">
                            লাইভ বাংলা ভয়েস কল ডায়ালার ও টেস্ট
                        </h4>
                        <span class="text-[10px] font-mono text-emerald-400">096 IP TSP Enabled</span>
                    </div>

                    <!-- Direct Dialer -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white block">যেকোনো ফোন নম্বরে এখনই টেস্ট কল করুন:</label>
                        <div class="flex items-center space-x-2">
                            <input type="text" x-model="dialPhone" placeholder="019XXXXXXXX"
                                   class="flex-1 bg-slate-900 border border-purple-500/40 rounded-xl px-3.5 py-2.5 text-xs font-bold text-purple-300 focus:outline-none focus:border-purple-400 font-mono">
                            
                            <button type="button" @click="dialCustomVoiceCall()" 
                                    :disabled="calling"
                                    class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-400 hover:to-pink-500 text-white font-bold text-xs uppercase flex items-center space-x-1.5 shadow-lg disabled:opacity-50 transition-all">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                                <span x-text="calling ? 'কল হচ্ছে...' : 'AI কল 📞'"></span>
                            </button>

                            <a :href="'tel:' + dialPhone" 
                               class="p-2.5 rounded-xl bg-emerald-600/20 text-emerald-400 border border-emerald-500/40 hover:bg-emerald-600 hover:text-white transition-all text-xs font-bold flex items-center justify-center" title="Alaap / ডায়ালারে সরাসরি কল ওপেন করুন">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                        </div>
                        <p class="text-[10px] text-slate-500">কলার আইডি: <b class="text-purple-300 font-mono">{{ $bdIpNumber ?: '09696xxxxxx' }} (Alaap / BD IP TSP)</b></p>
                    </div>

                    <!-- Idle Screen -->
                    <div x-show="!callActive && !voiceResult" class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/80 text-center space-y-2">
                        <div class="w-10 h-10 mx-auto rounded-full bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                            <i data-lucide="phone-call" class="w-5 h-5"></i>
                        </div>
                        <p class="text-xs text-slate-300 font-bold">ফোন নম্বর দিয়ে "AI কল" বাটনে চাপুন</p>
                        <p class="text-[11px] text-slate-500">স্পিকারে স্বয়ংক্রিয় স্পষ্ট বাংলায় ভয়েস কল শোনা যাবে</p>
                    </div>

                    <!-- Active Connected Screen -->
                    <div x-show="callActive" x-cloak class="p-4 rounded-2xl bg-purple-950/60 border border-purple-400/50 space-y-3 animate-pulse">
                        <div class="flex items-center justify-between text-purple-200">
                            <span class="font-bold flex items-center gap-2 text-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>📞 লাইভ কল চলমান: <b x-text="dialPhone" class="font-mono"></b></span>
                            </span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-bold font-mono">CONNECTED</span>
                        </div>
                        
                        <div class="p-3 rounded-xl bg-slate-950/80 border border-purple-800/60 text-xs text-slate-200 leading-relaxed italic" x-text="currentVoiceScript"></div>
                        
                        <div class="pt-2 flex items-center gap-2">
                            <button type="button" @click="confirmVoiceResponse('হ্যাঁ আমি অর্ডারটি কনফার্ম করছি')" 
                                    class="flex-1 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs transition-all shadow-lg flex items-center justify-center space-x-1.5">
                                <span>🗣️ বলুন: "হ্যাঁ নিব" (কনফার্ম)</span>
                            </button>
                            <button type="button" @click="confirmVoiceResponse('না বাতিল করুন')" 
                                    class="px-4 py-2 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs transition-all flex items-center justify-center space-x-1">
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

            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 4: গুগল অটো-এসইও ও সাইটম্যাপ -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'seo'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: Explanation & Action (6 Cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-3">
                        <div class="flex items-center space-x-2.5 text-amber-400">
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
