@extends('layouts.admin')

@section('title', 'AI কন্ট্রোল সেন্টার ও মার্কেটিং - Admin Panel')
@section('page-title', 'এআই সেলস অ্যাসিস্ট্যান্ট ও মার্কেটিং হাব')

@section('content')
<div class="space-y-8" x-data="aiControlCenter()">
    
    <!-- Top Hero Banner with AI Status -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-cyan-950/80 via-slate-900 to-indigo-950/80 border border-cyan-500/30 p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-mono font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                    <span>AI Sales Engine Active</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-cyber tracking-wide">
                    🤖 এআই সেলস ও মার্কেটিং কন্ট্রোল সেন্টার
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    সহজে আপনার ওয়েবসাইটের এআই সেলস বট পরিচালনা করুন, ১-ক্লিকে ফেসবুক বিজ্ঞাপনের লেখা তৈরি করুন এবং গুগল সার্চের জন্য এসইও অপ্টিমাইজ করুন।
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
                        <label class="text-xs font-bold text-slate-300">WhatsApp নম্বর (ওয়েবসাইট চ্যাট)</label>
                        <input type="text" name="whatsapp_number" value="{{ \App\Models\ThemeSetting::get('whatsapp_number', '+8801947521688') }}" placeholder="+8801947521688"
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

            <!-- Live Chat Mockup -->
            <div class="admin-glass rounded-3xl p-5 border border-slate-800 space-y-3">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                    <span class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>ওয়েবসাইট চ্যাটবট প্রিভিউ</span>
                    </span>
                    <span class="text-[10px] font-mono text-cyan-400">Live Simulator</span>
                </div>

                <div class="p-3 rounded-2xl bg-slate-950 border border-slate-800 space-y-2 text-xs">
                    <div class="flex items-start space-x-2">
                        <div class="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-[10px] shrink-0">🤖</div>
                        <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 text-[11px] leading-relaxed">{{ $botGreeting }}</div>
                    </div>

                    <div class="flex items-start space-x-2 justify-end">
                        <div class="p-2 rounded-xl bg-cyan-500 text-slate-950 font-bold text-[11px]">
                            ভাইয়া, অফার প্রাইস কত?
                        </div>
                        <div class="w-6 h-6 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center text-[10px] shrink-0">👤</div>
                    </div>
                </div>
            </div>

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

</div>
@endsection

@push('scripts')
<script>
    function aiControlCenter() {
        return {
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
            }
        }
    }
</script>
@endpush
