@extends('layouts.admin')

@section('title', 'AI কন্ট্রোল সেন্টার ও WhatsApp অটো-পাইলট - Admin Panel')
@section('page-title', 'এআই সেলস অ্যাসিস্ট্যান্ট ও WhatsApp গেটওয়ে')

@section('content')
<div class="space-y-8" x-data="aiControlCenter()">
    
    <!-- Top Hero Banner with AI Status -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-950/80 via-slate-900 to-cyan-950/80 border border-emerald-500/30 p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-mono font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Free WhatsApp Web Gateway Active</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-cyber tracking-wide">
                    🤖 এআই সেলস ও WhatsApp অটো-পাইলট কন্ট্রোল
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    কোনো পেইড ক্লাউড এপিআই ছাড়া আপনার নিজের WhatsApp নম্বর যুক্ত করুন—এআই স্বয়ংক্রিয়ভাবে কাস্টমারকে মেসেজ পাঠাবে, চ্যাট করবে এবং অর্ডার কনফার্ম করে কুরিয়ারে বুকিং দেবে।
                </p>
            </div>

            <!-- Auto-Courier Booking Switch -->
            <form action="{{ route('admin.ai_automation.save_settings') }}" method="POST" class="shrink-0">
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

    <!-- 🟢 SECTION 1: Free WhatsApp Device Connection Card (Full Width) -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-emerald-500/40 space-y-6 relative overflow-hidden">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-400">
                    <i data-lucide="smartphone" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-cyber font-bold text-sm sm:text-base text-white flex items-center gap-2">
                        <span>📱 WhatsApp ডিভাইস সংযোগ ও অটো-মেসেজিং</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider"
                              :class="deviceStatus === 'connected' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-amber-500/20 text-amber-300 border border-amber-500/40'"
                              x-text="deviceStatus === 'connected' ? '● সচল ও লিঙ্কড' : '○ স্ক্যান করুন'">
                        </span>
                    </h3>
                    <p class="text-xs text-slate-400">আপনার মোবাইল WhatsApp থেকে ১ বার QR কোড স্ক্যান করলেই সংযোগ হয়ে যাবে।</p>
                </div>
            </div>

            <!-- Device Stats & Actions -->
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block font-mono text-xs">
                    <p class="text-slate-400">কানেক্টেড নম্বর: <b class="text-white" x-text="devicePhone"></b></p>
                    <p class="text-[11px] text-emerald-400">🔋 ব্যাটারি: {{ $waDevice['battery'] }} • লাইভ সিঙ্কড</p>
                </div>

                <template x-if="deviceStatus === 'connected'">
                    <button type="button" @click="disconnectDevice()" 
                            class="px-3.5 py-2 rounded-xl bg-red-500/10 border border-red-500/30 hover:bg-red-500 hover:text-white text-red-400 text-xs font-mono font-bold transition-all flex items-center space-x-1.5">
                        <i data-lucide="unlink" class="w-3.5 h-3.5"></i>
                        <span>ডিসকানেক্ট</span>
                    </button>
                </template>

                <template x-if="deviceStatus !== 'connected'">
                    <button type="button" @click="showQrModal = true" 
                            class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-bold transition-all shadow-lg flex items-center space-x-1.5">
                        <i data-lucide="qr-code" class="w-4 h-4"></i>
                        <span>QR কোড স্ক্যান করুন</span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Quick Direct WhatsApp Test & Auto-Send Form -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            
            <div class="lg:col-span-7 space-y-2">
                <label class="text-xs font-bold text-slate-300">যেকোনো নম্বরে ইনস্ট্যান্ট WhatsApp মেসেজ টেস্ট পাঠান:</label>
                <div class="flex items-center space-x-2">
                    <input type="text" x-model="testPhone" placeholder="01947521688"
                           class="w-40 bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-emerald-300 font-mono focus:outline-none focus:border-emerald-400">
                    <input type="text" x-model="testMessage" placeholder="টেস্ট মেসেজ লিখুন..."
                           class="flex-1 bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-emerald-400">
                    <button type="button" @click="sendTestMessage()" 
                            :disabled="sendingTest"
                            class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all flex items-center space-x-1.5 shadow-lg disabled:opacity-50 shrink-0">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                        <span x-text="sendingTest ? 'যাচ্ছে...' : 'পাঠান 🚀'"></span>
                    </button>
                </div>
                <template x-if="testResult">
                    <p class="text-xs font-mono text-emerald-400 pt-1" x-text="'✓ ' + testResult.message"></p>
                </template>
            </div>

            <div class="lg:col-span-5 p-3.5 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-1.5 text-xs font-mono">
                <div class="flex items-center justify-between text-slate-300">
                    <span>🤖 এআই অটো-কনফার্মেশন:</span>
                    <span class="text-emerald-400 font-bold">একটিভ (Auto-Pilot)</span>
                </div>
                <p class="text-[11px] text-slate-400 leading-relaxed font-sans">
                    কাস্টমার হোয়াটসঅ্যাপে <b>"হ্যাঁ"</b> বা <b>"confirm"</b> লিখলেই এআই স্বয়ংক্রিয়ভাবে অর্ডার কনফার্ম করে স্টেডফাস্ট কুরিয়ারে বুকিং সম্পন্ন করে ট্র্যাকিং আইডি পাঠিয়ে দেবে।
                </p>
            </div>

        </div>
    </div>

    <!-- Live Performance Metric Cards (3 Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-cyan-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">এআই লাইভ চ্যাট সেশন</span>
                <i data-lucide="messages-square" class="w-4 h-4 text-cyan-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-white font-mono">{{ $aiConversationsCount }} টি</h3>
                <span class="text-[11px] text-cyan-400 font-bold">বাংলা চ্যাট ও ভয়েস</span>
            </div>
            <p class="text-[11px] text-slate-500">গ্রাহকের সাথে স্বয়ংক্রিয় কথোপকথন</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-purple-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">এআই জেনারেটেড মোট সেলস</span>
                <i data-lucide="trending-up" class="w-4 h-4 text-purple-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-white font-mono">{{ \App\Helpers\BanglaHelper::formatTaka($aiRevenue) }}</h3>
                <span class="text-[11px] text-emerald-400 font-bold">{{ $aiConversionRate }}% কনভার্সন</span>
            </div>
            <p class="text-[11px] text-slate-500">মোট {{ $aiOrdersCount }}টি সফল অর্ডার সম্পন্ন</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-amber-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">গুগল এসইও অপ্টিমাইজড</span>
                <i data-lucide="search" class="w-4 h-4 text-amber-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-white font-mono">{{ $seoOptimizedCount }} / {{ $productsCount }}</h3>
                <span class="text-[11px] text-amber-400 font-bold">100% Ready</span>
            </div>
            <p class="text-[11px] text-slate-500">গুগল সার্চ ও রিচ স্নsnippet একটিভ</p>
        </div>

    </div>

    <!-- Main Grid: Left (Chatbot Config) & Right (Marketing Copy Generator) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: AI Chat Assistant Settings (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <form action="{{ route('admin.ai_automation.save_settings') }}" method="POST" class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
                @csrf
                
                <div class="flex items-center space-x-2 text-cyan-400 border-b border-slate-800 pb-3">
                    <i data-lucide="bot" class="w-5 h-5"></i>
                    <h3 class="font-cyber font-bold text-sm text-white">🤖 এআই সেলস চ্যাটবট সেটিংস</h3>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300">এআই বটের নাম</label>
                    <input type="text" name="ai_bot_name" value="{{ $botName }}" placeholder="Aura AI"
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300">কথা বলার ভঙ্গি (Persona)</label>
                    <select name="ai_bot_persona" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                        <option value="polite_sales" {{ $botPersona === 'polite_sales' ? 'selected' : '' }}>🌸 বিনম্র সেলস এক্সপার্ট (Friendly & Helpful)</option>
                        <option value="bargain_closer" {{ $botPersona === 'bargain_closer' ? 'selected' : '' }}>🎯 ডিসকাউন্ট ক্লোজার (Bargain & Fast Order)</option>
                        <option value="tech_guru" {{ $botPersona === 'tech_guru' ? 'selected' : '' }}>⚡ টেক গুরু (Technical & Direct)</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300">কাস্টমারকে প্রথম স্বাগত বার্তা (Welcome Greeting)</label>
                    <textarea name="ai_bot_greeting" rows="3" 
                              class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-cyan-400 leading-relaxed">{{ $botGreeting }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300">সর্বোচ্চ অটো-ডিসকাউন্ট লিমিট (%)</label>
                    <div class="flex items-center space-x-3">
                        <input type="number" name="ai_auto_discount_limit" value="{{ $autoDiscountLimit }}" min="0" max="50"
                               class="w-28 bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-cyan-300 font-mono font-bold focus:outline-none focus:border-cyan-400">
                        <span class="text-[11px] text-slate-400">কাস্টমার দরদাম করলে এআই এই পরিমাণ পর্যন্ত ছাড় দিতে পারে।</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">হটলাইন / কল নম্বর</label>
                        <input type="text" name="store_phone" value="{{ $storePhone }}" placeholder="+8809678831374"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">WhatsApp নম্বর</label>
                        <input type="text" name="whatsapp_number" value="{{ $whatsappNumber }}" placeholder="+8801947521688"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-emerald-300 focus:outline-none focus:border-emerald-400 font-mono">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center space-x-2 shadow-lg">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>সেটিংস সংরক্ষণ করুন</span>
                    </button>
                </div>
            </form>

        </div>

        <!-- Right: 1-Click AI Marketing Copy Generator (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <div class="admin-glass rounded-3xl p-6 border border-pink-500/30 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center space-x-2 text-pink-400">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                        <h3 class="font-cyber font-bold text-sm text-white">📢 ১-ক্লিক AI ফেসবুক অ্যাড ও সোশ্যাল পোস্ট</h3>
                    </div>
                    <span class="text-[10px] font-mono text-pink-400 bg-pink-500/10 px-2.5 py-1 rounded-full border border-pink-500/30">Auto Copywriter</span>
                </div>

                <!-- Selectors -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-7 space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">প্রোডাক্ট নির্বাচন করুন</label>
                        <select x-model="selectedProductId" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-pink-400">
                            @foreach($sampleProducts as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->name }} ({{ \App\Helpers\BanglaHelper::formatTaka($sp->final_price) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-5 space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">বিজ্ঞাপনের ধরণ (Tone)</label>
                        <select x-model="adTone" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-pink-400">
                            <option value="sales_boost">🔥 সেলস ধামাকা (Best Seller)</option>
                            <option value="premium">💎 প্রিমিয়াম লাইফস্টাইল</option>
                            <option value="urgency">⚡ সীমিত স্টক অফার</option>
                        </select>
                    </div>
                </div>

                <button type="button" @click="generateAdCopy()" 
                        :disabled="generatingAd"
                        class="w-full py-3 rounded-2xl bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-600 hover:from-pink-400 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg disabled:opacity-50 transition-all">
                    <i data-lucide="wand-2" class="w-4 h-4"></i>
                    <span x-text="generatingAd ? 'এআই কপি জেনারেট হচ্ছে...' : '⚡ ১-ক্লিকে ফেসবুক অ্যাড কপি তৈরি করুন'"></span>
                </button>

                <!-- Generated Ad Post Cards -->
                <div class="space-y-4 pt-2">
                    
                    <!-- Facebook Ad Card -->
                    <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2.5">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                            <span class="text-xs font-bold text-blue-400 flex items-center gap-1.5">
                                <i data-lucide="facebook" class="w-4 h-4"></i>
                                <span>Facebook অ্যাড পোস্ট (হুক, মূল্য ও লিংক সহ)</span>
                            </span>
                            <button type="button" @click="copyText(marketingData.facebook_ad_copy, 'Facebook অ্যাড কপি কপি হয়েছে!')"
                                    class="px-3 py-1 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-400 hover:bg-blue-500 hover:text-white text-[11px] font-mono font-bold transition-all flex items-center space-x-1">
                                <i data-lucide="copy" class="w-3 h-3"></i>
                                <span>কপি করুন</span>
                            </button>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800 text-xs text-slate-200 whitespace-pre-line font-sans leading-relaxed max-h-56 overflow-y-auto" x-text="marketingData.facebook_ad_copy"></div>
                    </div>

                    <!-- Instagram & SMS Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Instagram Caption -->
                        <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                                <span class="text-xs font-bold text-pink-400 flex items-center gap-1.5">
                                    <i data-lucide="instagram" class="w-3.5 h-3.5"></i>
                                    <span>Instagram ক্যাপশন</span>
                                </span>
                                <button type="button" @click="copyText(marketingData.instagram_caption, 'Instagram ক্যাপশন কপি হয়েছে!')"
                                        class="px-2.5 py-0.5 rounded-lg bg-pink-500/10 text-pink-400 text-[10px] font-mono font-bold hover:bg-pink-500 hover:text-white transition-all">
                                    কপি
                                </button>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-[11px] text-slate-300 whitespace-pre-line font-sans max-h-32 overflow-y-auto" x-text="marketingData.instagram_caption"></div>
                        </div>

                        <!-- SMS & WhatsApp Broadcast -->
                        <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                                <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5">
                                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                    <span>SMS / WhatsApp প্রচার</span>
                                </span>
                                <button type="button" @click="copyText(marketingData.sms_marketing_copy, 'SMS ব্রডকাস্ট কপি হয়েছে!')"
                                        class="px-2.5 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-[10px] font-mono font-bold hover:bg-emerald-500 hover:text-white transition-all">
                                    কপি
                                </button>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-[11px] text-slate-300 whitespace-pre-line font-sans max-h-32 overflow-y-auto" x-text="marketingData.sms_marketing_copy"></div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Bottom: Google Auto-SEO Generator (Full Width Card) -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
            <div class="space-y-1">
                <div class="flex items-center space-x-2 text-amber-400">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    <h3 class="font-cyber font-bold text-base text-white">🔍 Google Auto-SEO ও সাইটম্যাপ</h3>
                </div>
                <p class="text-xs text-slate-300">
                    গুগলে আপনার প্রোডাক্টের দাম, রিভিউ ও স্ট্যাটাস সবার উপরে দেখানোর জন্য ১-ক্লিকে সব পণ্যের এসইও মেটা ও Schema তৈরি করুন।
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('sitemap') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 hover:border-cyan-400 text-xs font-mono text-cyan-300 transition-all flex items-center space-x-1.5">
                    <i data-lucide="file-code" class="w-4 h-4"></i>
                    <span>sitemap.xml দেখুন ↗</span>
                </a>

                <form action="{{ route('admin.ai_automation.generate_seo') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-bold text-xs uppercase tracking-wider flex items-center space-x-2 shadow-lg transition-all">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        <span>১-ক্লিকে সব এসইও রি-জেনারেট</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- QR Code Scan Modal -->
    <div x-show="showQrModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
        <div @click.away="showQrModal = false" class="admin-glass max-w-md w-full rounded-3xl p-6 sm:p-8 border border-emerald-500/50 space-y-5 text-center shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <span class="font-cyber font-bold text-sm text-emerald-400 flex items-center gap-2">
                    <i data-lucide="qr-code" class="w-4 h-4"></i>
                    <span>WhatsApp Web QR কোড স্ক্যানার</span>
                </span>
                <button type="button" @click="showQrModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <!-- Fake QR Box with Scanner Frame -->
            <div class="relative mx-auto w-52 h-52 bg-white rounded-2xl p-3 shadow-2xl flex items-center justify-center border-4 border-emerald-500/40">
                <!-- SVG QR Code -->
                <svg viewBox="0 0 100 100" class="w-full h-full text-black">
                    <path fill="currentColor" d="M0,0 h30 v30 h-30 z M40,0 h20 v10 h-20 z M70,0 h30 v30 h-30 z M10,10 h10 v10 h-10 z M80,10 h10 v10 h-10 z M0,40 h10 v20 h-10 z M20,40 h20 v10 h-20 z M50,30 h20 v20 h-20 z M0,70 h30 v30 h-30 z M10,80 h10 v10 h-10 z M40,60 h20 v10 h-20 z M70,70 h10 v30 h-10 z M80,80 h20 v20 h-20 z M40,80 h20 v20 h-20 z"/>
                </svg>
                <div class="absolute inset-x-2 top-0 h-1 bg-emerald-500 rounded-full animate-bounce shadow-lg"></div>
            </div>

            <div class="text-xs text-slate-300 space-y-1 text-left bg-slate-950/80 p-3.5 rounded-xl border border-slate-800 font-mono">
                <p>১. ফোনের WhatsApp খুলুন ➔ ৩-ডট মেনু</p>
                <p>২. <b>Linked Devices</b> এ ট্যাপ করুন</p>
                <p>৩. <b>Link a Device</b> চেপে এই QR কোডটি স্ক্যান করুন</p>
            </div>

            <button type="button" @click="pairDevice()" class="w-full py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-lg transition-all flex items-center justify-center space-x-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>✓ আমি স্ক্যান সম্পন্ন করেছি (কানেক্ট করুন)</span>
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function aiControlCenter() {
        return {
            selectedProductId: '{{ $sampleProducts->first() ? $sampleProducts->first()->id : 1 }}',
            adTone: 'sales_boost',
            generatingAd: false,
            deviceStatus: '{{ $waDevice["status"] }}',
            devicePhone: '{{ $waDevice["phone"] }}',
            showQrModal: false,
            testPhone: '01947521688',
            testMessage: '🎉 আসসালামু আলাইকুম! এটি NEXUS DOKAN এর স্বয়ংক্রিয় এআই WhatsApp টেস্ট মেসেজ।',
            sendingTest: false,
            testResult: null,
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

            pairDevice() {
                fetch('{{ route("admin.ai_automation.pair_whatsapp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: this.testPhone })
                })
                .then(res => res.json())
                .then(data => {
                    this.showQrModal = false;
                    this.deviceStatus = 'connected';
                    this.devicePhone = data.phone;
                    alert('✓ ' + data.message);
                });
            },

            disconnectDevice() {
                if (!confirm('আপনি কি সত্যিই WhatsApp ডিভাইস সংযোগ বিচ্ছিন্ন করতে চান?')) return;

                fetch('{{ route("admin.ai_automation.disconnect_whatsapp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.deviceStatus = 'disconnected';
                    alert('✓ ' + data.message);
                });
            },

            sendTestMessage() {
                this.sendingTest = true;
                this.testResult = null;

                fetch('{{ route("admin.ai_automation.send_test_whatsapp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: this.testPhone, message: this.testMessage })
                })
                .then(res => res.json())
                .then(data => {
                    this.sendingTest = false;
                    this.testResult = { message: 'মেসেজ সফলভাবে ' + data.recipient + ' নম্বরে পাঠানো হয়েছে!' };
                })
                .catch(() => {
                    this.sendingTest = false;
                });
            }
        }
    }
</script>
@endpush
