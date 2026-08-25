@extends('layouts.admin')

@section('page-title', 'AI অটোমেশন কন্ট্রোল সেন্টার')

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
                        AI অটোমেশন কন্ট্রোল সেন্টার
                    </h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[11px] font-mono font-bold flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>সিস্টেম লাইভ ও সক্রিয়</span>
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1 max-w-xl">
                    ওয়েবসাইট সেলস চ্যাটবট, WhatsApp ১-ক্লিক ভেরিফিকেশন, বাংলা ভয়েস কল এবং গুগল অটো-এসইও—সবকিছু এক জায়গা থেকে সহজে নিয়ন্ত্রণ করুন।
                </p>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex items-center gap-3 relative z-10">
            <a href="{{ route('admin.live_chat.index') }}" 
               class="px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-cyan-400 text-xs font-bold text-slate-200 hover:text-cyan-300 transition-all flex items-center space-x-2 shadow-sm">
                <i data-lucide="messages-square" class="w-4 h-4 text-cyan-400"></i>
                <span>লাইভ চ্যাট ডেস্ক ↗</span>
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

    <!-- 4 Clean Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-purple-500/40 transition-all cursor-pointer" @click="activeTab = 'chat'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">এআই সেলস অটো-পাইলট</span>
                <i data-lucide="bot" class="w-4 h-4 text-cyan-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-xl font-black text-white font-mono">২৪/৭ সক্রিয়</h3>
                <span class="text-[11px] text-emerald-400 font-bold">Auto Booking</span>
            </div>
            <p class="text-[11px] text-slate-500">গ্রাহকদের সাথে চ্যাট করে অর্ডার বুক করে</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-emerald-500/40 transition-all cursor-pointer" @click="activeTab = 'whatsapp'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">WhatsApp ভেরিফাইড</span>
                <i data-lucide="message-circle" class="w-4 h-4 text-emerald-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-xl font-black text-emerald-400 font-mono">{{ $verifiedOrdersCount }} টি অর্ডার</h3>
                <span class="text-[11px] text-slate-400">১-ক্লিক নিশ্চিতকরণ</span>
            </div>
            <p class="text-[11px] text-slate-500">'হ্যাঁ' রিপ্লাইয়ে সরাসরি কুরিয়ারে বুকিং</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-purple-500/40 transition-all cursor-pointer" @click="activeTab = 'voice'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">বাংলা ভয়েস কলিং</span>
                <i data-lucide="phone-call" class="w-4 h-4 text-purple-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-xl font-black text-purple-300 font-mono">Bangla TTS</h3>
                <span class="text-[11px] text-purple-400 font-bold">Ready</span>
            </div>
            <p class="text-[11px] text-slate-500">স্বয়ংক্রিয় স্পষ্ট বাংলায় ভয়েস কল</p>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-1.5 hover:border-cyan-500/40 transition-all cursor-pointer" @click="activeTab = 'seo'">
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-xs font-medium">গুগল এসইও অপ্টিমাইজড</span>
                <i data-lucide="globe" class="w-4 h-4 text-amber-400"></i>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-xl font-black text-white font-mono">{{ $seoOptimizedCount }} / {{ $productsCount }} পণ্য</h3>
                <span class="text-[11px] text-cyan-400 font-bold">১০০% রেডি</span>
            </div>
            <p class="text-[11px] text-slate-500">Google Rich Snippets & Sitemap</p>
        </div>

    </div>

    <!-- Clean Tabbed Interface (Organized & Easy) -->
    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-6">
        
        <!-- Tab Navigation Bar -->
        <div class="flex items-center gap-2 border-b border-slate-800 pb-4 overflow-x-auto select-none">
            <button type="button" @click="activeTab = 'chat'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'chat' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="bot" class="w-4 h-4"></i>
                <span>১. এআই সেলস চ্যাটবট (Auto-Pilot)</span>
            </button>

            <button type="button" @click="activeTab = 'whatsapp'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'whatsapp' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>২. WhatsApp ১-ক্লিক ভেরিফিকেশন</span>
            </button>

            <button type="button" @click="activeTab = 'voice'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'voice' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                <span>৩. বাংলা ভয়েস কলিং এজেন্ট</span>
            </button>

            <button type="button" @click="activeTab = 'seo'" 
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shrink-0"
                    :class="activeTab === 'seo' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900'">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>৪. গুগল অটো-এসইও ও সাইটম্যাপ</span>
            </button>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 1: এআই সেলস চ্যাটবট ও অটো-পাইলট -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'chat'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left: Explanatory & Controls (7 Cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-cyber font-bold text-sm text-white flex items-center gap-2">
                                <i data-lucide="zap" class="w-4 h-4 text-cyan-400"></i>
                                <span>অটো-পাইলট সেলস ইঞ্জিন কীভাবে কাজ করে?</span>
                            </h4>
                            <span class="px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 text-[10px] font-mono font-bold">24/7 LIVE</span>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            কোনো ভিজিটর আপনার ওয়েবসাইটে আসলে নিচের ডানপাশের এআই অ্যাসিস্ট্যান্টের সাথে কথা বলতে পারে। ভিজিটর কোনো প্রোডাক্টের দাম জানতে চাইলে বা কিনতে চাইলে এআই সরাসরি চ্যাটেই অর্ডার তৈরি করে ফেলে—কোনো ফর্ম পূরণের ঝামেলা ছাড়াই!
                        </p>

                        <!-- 3 Step Workflow -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                            <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800 space-y-1">
                                <span class="w-5 h-5 rounded-full bg-cyan-500/20 text-cyan-300 text-xs font-bold font-mono flex items-center justify-center">১</span>
                                <h5 class="text-xs font-bold text-white">পণ্য অনুসন্ধান</h5>
                                <p class="text-[10px] text-slate-400 leading-tight">গ্রাহক লিখলেই সাথে সাথে ছবি ও দাম দেখায়</p>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800 space-y-1">
                                <span class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-300 text-xs font-bold font-mono flex items-center justify-center">২</span>
                                <h5 class="text-xs font-bold text-white">ইন-চ্যাট বুকিং</h5>
                                <p class="text-[10px] text-slate-400 leading-tight">নাম ও ফোন নম্বর নিয়ে চ্যাটে অর্ডার কনফার্ম করে</p>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800 space-y-1">
                                <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold font-mono flex items-center justify-center">৩</span>
                                <h5 class="text-xs font-bold text-white">সরাসরি নোটিফিকেশন</h5>
                                <p class="text-[10px] text-slate-400 leading-tight">অ্যাডমিন ড্যাশবোর্ডে সাথে সাথে অর্ডার চলে আসে</p>
                            </div>
                        </div>
                    </div>

                    <!-- Direct Action Card -->
                    <div class="p-4 rounded-2xl bg-cyan-950/20 border border-cyan-500/30 flex items-center justify-between">
                        <div class="space-y-0.5">
                            <h5 class="text-xs font-bold text-white">লাইভ চ্যাট ও সাপোর্ট ডেস্ক</h5>
                            <p class="text-[11px] text-slate-400">গ্রাহকদের রিয়েল-টাইম মেসেজ দেখতে ও উত্তর দিতে সাপোর্ট ডেস্ক খুলুন</p>
                        </div>
                        <a href="{{ route('admin.live_chat.index') }}" class="px-4 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs transition-all flex items-center space-x-1.5 shrink-0 shadow-lg">
                            <span>সাপোর্ট ডেস্ক 💬</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Mockup / Demonstration Card (5 Cols) -->
                <div class="lg:col-span-5 p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                        <div class="flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                            <span class="text-xs font-bold text-white">চ্যাটবট লাইভ ইন্টারঅ্যাকশন প্রিভিউ</span>
                        </div>
                        <span class="text-[10px] font-mono text-cyan-400">Bangla & English</span>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-start space-x-2">
                            <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center text-[10px] text-slate-300 shrink-0">👤</div>
                            <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200">
                                ভাইয়া, ভালো নয়েজ ক্যানসেলিং ইয়ারবাডস আছে?
                            </div>
                        </div>

                        <div class="flex items-start space-x-2 justify-end">
                            <div class="p-2.5 rounded-xl bg-gradient-to-r from-purple-900/60 to-cyan-900/60 border border-cyan-500/30 text-white text-right max-w-[85%]">
                                🤖 আমাদের <b>AuraBlade ANC Pro</b> আছে! 48dB নয়েজ ক্যানসেলেশন ও 40 ঘণ্টা ব্যাটারি। দাম মাত্র <b>৳২,৯৫০</b>।<br>
                                <span class="text-emerald-300 font-bold">অর্ডার করতে আপনার নাম ও ফোন নম্বর লিখুন!</span>
                            </div>
                            <div class="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-[10px] shrink-0">🤖</div>
                        </div>

                        <div class="flex items-start space-x-2">
                            <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center text-[10px] text-slate-300 shrink-0">👤</div>
                            <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200">
                                আমি এক পিস নিব। নাম: সাদি, ফোন: 01947521688, ঠিকানা: উত্তরা ঢাকা
                            </div>
                        </div>

                        <div class="flex items-start space-x-2 justify-end">
                            <div class="p-2.5 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-right max-w-[85%]">
                                🎉 <b>অর্ডার সফলভাবে বুক হয়েছে!</b><br>
                                অর্ডার নম্বর: <b>#NX-2026-8912</b>। শীঘ্রই আমাদের প্রতিনিধি কনফার্মেশন কল করবেন।
                            </div>
                            <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-[10px] shrink-0">✓</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 2: WhatsApp ১-ক্লিক ভেরিফিকেশন -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'whatsapp'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: Explanation & WhatsApp URL Builder (6 Cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-3">
                        <div class="flex items-center space-x-2.5 text-emerald-400">
                            <i data-lucide="message-square-quote" class="w-5 h-5"></i>
                            <h4 class="font-cyber font-bold text-sm text-white">WhatsApp অর্ডার নিশ্চিতকরণ কীভাবে কাজ করে?</h4>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            কোনো কাস্টমার অর্ডার করার পর অ্যাডমিন প্যানেলে অর্ডারের পাশে <b class="text-emerald-400">[ Send WhatsApp Prompt 💬 ]</b> বাটন থাকে। ক্লিক করলে সাথে সাথে কাস্টমারের হোয়াটসঅ্যাপে প্রি-ফিল্ড বাংলা মেসেজ ওপেন হয়।
                        </p>
                        <div class="p-3 rounded-xl bg-emerald-950/30 border border-emerald-500/30 text-emerald-300 text-xs space-y-1">
                            <p class="font-bold">⚡ অটো-কুরিয়ার সুবিধা:</p>
                            <p class="text-slate-300 text-[11px]">কাস্টমার মেসেজের উত্তরে <b>"হ্যাঁ"</b>, <b>"confirm"</b> বা <b>"পাঠিয়ে দিন"</b> লিখলেই অর্ডারটি স্বয়ংক্রিয়ভাবে <b>Processing</b> স্ট্যাটাসে চলে যাবে এবং স্টেডফাস্ট কুরিয়ারে ট্র্যাকিং আইডি সহ বুক হয়ে যাবে।</p>
                        </div>
                    </div>

                    <!-- Live Message Preview Box -->
                    <div class="p-4 rounded-2xl bg-[#0b141a] border border-emerald-500/30 space-y-2 text-xs font-sans text-slate-200">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-800 text-[11px] text-emerald-400">
                            <span class="font-bold flex items-center gap-1.5"><i data-lucide="check-check" class="w-3.5 h-3.5"></i> WhatsApp Message Format</span>
                            <span class="font-mono text-slate-500">100% Free wa.me</span>
                        </div>
                        <div class="p-3 rounded-xl bg-[#202c33] border border-slate-700/60 leading-relaxed text-[11px]">
                            আসসালামু আলাইকুম <b>শেয়খ সাদী</b> ভাই!<br><br>
                            NEXUS DOKAN থেকে আপনার অর্ডারটি প্রস্তুত করা হচ্ছে:<br>
                            📦 <b>পণ্য:</b> AuraBlade ANC Cyber Earbuds Pro (১ পিস)<br>
                            💵 <b>বিল:</b> ৳৩,০১০ (ক্যাশ অন ডেলিভারি)<br>
                            📍 <b>ঠিকানা:</b> উত্তরা, ঢাকা<br><br>
                            অর্ডারটি নিশ্চিত করতে অনুগ্রহ করে <b>"হ্যাঁ"</b> অথবা বাতিল করতে <b>"না"</b> লিখে রিপ্লাই দিন।
                        </div>
                    </div>
                </div>

                <!-- Right: Interactive WhatsApp Simulation Tool (6 Cols) -->
                <div class="lg:col-span-6 p-5 rounded-2xl bg-slate-950 border border-emerald-500/30 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                        <h4 class="font-cyber font-bold text-xs text-emerald-400 uppercase tracking-wider">
                            WhatsApp রিপ্লাই সিমুলেশন ও টেস্ট টুল
                        </h4>
                        <span class="text-[10px] font-mono text-slate-500">Meta Webhook Test</span>
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
                            <span class="text-[10px] text-slate-500">ক্লিক করে দ্রুত টেস্ট করুন:</span>
                            <button type="button" @click="waInput = 'হ্যাঁ'; testWhatsAppReply()" class="px-2 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[10px] text-slate-300 hover:text-white">"হ্যাঁ"</button>
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
        <!-- TAB 3: এআই ভয়েস কলিং এজেন্ট -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'voice'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: Explanatory & Controls (6 Cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-3">
                        <div class="flex items-center space-x-2.5 text-purple-400">
                            <i data-lucide="phone-forwarded" class="w-5 h-5"></i>
                            <h4 class="font-cyber font-bold text-sm text-white">বাংলা এআই ভয়েস কলিং কীভাবে কাজ করে?</h4>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            কাস্টমার কোনো অর্ডার করলে আপনি সরাসরি ব্রাউজার থেকেই স্পষ্ট স্বাভাবিক বাংলায় স্বয়ংক্রিয় ফোন কল করতে পারেন। এআই ভার্চুয়াল অ্যাসিস্ট্যান্ট অর্ডারের তথ্য ও বিল পড়ে শোনাবে এবং কাস্টমার মুখে "হ্যাঁ" বা "না" বললে স্বয়ংক্রিয়ভাবে অর্ডার প্রসেস করবে।
                        </p>
                        <div class="p-3 rounded-xl bg-purple-950/30 border border-purple-500/30 text-purple-300 text-xs space-y-1">
                            <p class="font-bold">🎙️ স্পিচ ইঞ্জিন:</p>
                            <p class="text-slate-300 text-[11px]">ব্রাউজারের বিল্ট-ইন বাংলা ভয়েস সিন্থেসিস ইঞ্জিন (SpeechSynthesis API) ব্যবহার করে লাইভ কথা বলে।</p>
                        </div>
                    </div>

                    <!-- Direct Dialer Card -->
                    <div class="p-5 rounded-2xl bg-slate-950 border border-purple-500/30 space-y-3">
                        <label class="text-xs font-bold text-white block">যেকোনো নম্বরে এখনই টেস্ট কল করুন:</label>
                        <div class="flex items-center space-x-2">
                            <input type="text" x-model="dialPhone" placeholder="019XXXXXXXX"
                                   class="flex-1 bg-slate-900 border border-purple-500/40 rounded-xl px-3.5 py-2.5 text-xs font-bold text-purple-300 focus:outline-none focus:border-purple-400 font-mono">
                            <button type="button" @click="dialCustomVoiceCall()" 
                                    :disabled="calling"
                                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-400 hover:to-pink-500 text-white font-bold text-xs uppercase flex items-center space-x-2 shadow-lg disabled:opacity-50 transition-all">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                                <span x-text="calling ? 'কল হচ্ছে...' : 'কল করুন 📞'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right: Active Call Screen Mockup (6 Cols) -->
                <div class="lg:col-span-6 p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                        <h4 class="font-cyber font-bold text-xs text-purple-400 uppercase tracking-wider">
                            লাইভ কল মনিটর ও রেসপন্স
                        </h4>
                        <span class="text-[10px] font-mono text-slate-500">Live Audio Dispatch</span>
                    </div>

                    <!-- Idle Screen -->
                    <div x-show="!callActive && !voiceResult" class="p-8 rounded-2xl bg-slate-900/40 border border-slate-800/80 text-center space-y-2">
                        <div class="w-12 h-12 mx-auto rounded-full bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                            <i data-lucide="phone-call" class="w-6 h-6"></i>
                        </div>
                        <p class="text-xs text-slate-300 font-bold">ফোন নম্বর দিয়ে "কল করুন" বাটনে চাপুন</p>
                        <p class="text-[11px] text-slate-500">স্পিকারে স্বয়ংক্রিয় বাংলায় ভয়েস কল শোনা যাবে</p>
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

        <!-- ================================================================= -->
        <!-- TAB 4: গুগল অটো-এসইও ও সাইটম্যাপ -->
        <!-- ================================================================= -->
        <div x-show="activeTab === 'seo'" x-cloak class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: Explanation & Action (6 Cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-3">
                        <div class="flex items-center space-x-2.5 text-amber-400">
                            <i data-lucide="sparkles" class="w-5 h-5"></i>
                            <h4 class="font-cyber font-bold text-sm text-white">Google Auto-SEO & Schema কীভাবে কাজ করে?</h4>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            আপনার স্টোরের প্রতিটি প্রোডাক্টের জন্য স্বয়ংক্রিয়ভাবে গুগল সার্চ ফ্রেন্ডলি মেটা টাইটেল, বাংলা ও ইংরেজি ডেসক্রিপশন এবং JSON-LD Rich Snippet Schema তৈরি করে রাখা হয়। এতে গুগলে সার্চ করলে সরাসরি স্টার রেটিং, দাম ও ইন-স্টক স্ট্যাটাস শো করে।
                        </p>
                        <div class="flex items-center gap-3 pt-2">
                            <a href="{{ route('sitemap') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 hover:border-cyan-400 text-xs font-mono text-cyan-300 transition-all flex items-center space-x-1.5">
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
                <div class="lg:col-span-6 p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
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
